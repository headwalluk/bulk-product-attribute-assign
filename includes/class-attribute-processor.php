<?php
/**
 * Product attribute processor.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Attribute Processor class.
 *
 * Core logic for processing products and assigning attributes.
 *
 * @since 1.0.0
 */
class Attribute_Processor {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialization if needed.
	}

	/**
	 * Get products to process.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $args Query arguments.
	 *
	 * @return array<int> Array of product IDs.
	 */
	public function get_products_to_process( array $args = array() ): array {
		$defaults = array(
			'include_virtual' => false,
			'limit'           => -1,
			'offset'          => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		// Build WooCommerce product query args.
		$query_args = array(
			'status' => 'publish',
			'limit'  => $args['limit'],
			'offset' => $args['offset'],
			'return' => 'ids',
		);

		// Exclude virtual/downloadable products if not included.
		if ( ! $args['include_virtual'] ) {
			$query_args['virtual']      = false;
			$query_args['downloadable'] = false;
		}

		// Suspend cache addition for large queries to prevent object cache saturation.
		wp_suspend_cache_addition( true );

		// Get products.
		$product_ids = wc_get_products( $query_args );

		// Resume cache addition.
		wp_suspend_cache_addition( false );

		return is_array( $product_ids ) ? $product_ids : array();
	}

	/**
	 * Process a single product.
	 *
	 * @since 1.0.0
	 *
	 * @param int        $product_id Product ID.
	 * @param string     $attribute  Attribute name.
	 * @param array<int> $term_ids   Term IDs to assign.
	 * @param string     $mode       Mode: 'add' or 'replace'.
	 * @param bool       $dry_run    Whether this is a dry run (no actual changes).
	 *
	 * @return bool|\WP_Error True on success, WP_Error on failure.
	 */
	public function process_single_product( int $product_id, string $attribute, array $term_ids, string $mode, bool $dry_run = false ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return new \WP_Error(
				'invalid_product',
				sprintf(
					/* translators: %d: Product ID */
					__( 'Product %d not found.', 'bulk-product-attribute-assign' ),
					$product_id
				)
			);
		}

		// Get taxonomy name (adds 'pa_' prefix).
		$taxonomy = wc_attribute_taxonomy_name( $attribute );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return new \WP_Error(
				'invalid_taxonomy',
				sprintf(
					/* translators: %s: Taxonomy name */
					__( 'Taxonomy %s does not exist.', 'bulk-product-attribute-assign' ),
					$taxonomy
				)
			);
		}

		// Validate term IDs.
		$valid_term_ids = array();
		foreach ( $term_ids as $term_id ) {
			$term = get_term( $term_id, $taxonomy );
			if ( ! is_wp_error( $term ) && $term ) {
				$valid_term_ids[] = (int) $term_id;
			}
		}

		if ( empty( $valid_term_ids ) ) {
			return new \WP_Error(
				'no_valid_terms',
				__( 'No valid terms provided.', 'bulk-product-attribute-assign' )
			);
		}

		// If dry run, just return success without making changes.
		if ( $dry_run ) {
			return true;
		}

		// Process parent product.
		$result = $this->assign_terms_to_product( $product, $taxonomy, $valid_term_ids, $mode );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// If variable product, we've already set the terms on the parent.
		// Variations inherit from parent - we don't need to process them separately.

		return true;
	}

	/**
	 * Assign terms to a product or variation.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Product $product  Product object.
	 * @param string      $taxonomy Taxonomy name.
	 * @param array<int>  $term_ids Term IDs to assign.
	 * @param string      $mode     Mode: 'add' or 'replace'.
	 *
	 * @return bool|\WP_Error True on success, WP_Error on failure.
	 */
	private function assign_terms_to_product( \WC_Product $product, string $taxonomy, array $term_ids, string $mode ) {
		$product_id = $product->get_id();

		// Get attribute name without 'pa_' prefix.
		$attribute_name = str_replace( 'pa_', '', $taxonomy );

		// Get existing terms for this taxonomy (fields => 'ids' is more efficient).
		$existing_terms = wp_get_object_terms( $product_id, $taxonomy, array( 'fields' => 'ids' ) );

		if ( is_wp_error( $existing_terms ) ) {
			return $existing_terms;
		}

		// Ensure existing terms are integers.
		$existing_terms = array_map( 'intval', $existing_terms );

		// Determine final term IDs based on mode.
		if ( DEF_MODE_REPLACE === $mode ) {
			$final_term_ids = $term_ids;
		} else {
			// Add mode - merge with existing.
			$final_term_ids = array_unique( array_merge( $existing_terms, $term_ids ) );
		}

		// Ensure all term IDs are integers and remove any empty values.
		$final_term_ids = array_map( 'intval', array_filter( $final_term_ids ) );

		// Set taxonomy terms (required for filtering and queries).
		$result = wp_set_object_terms( $product_id, $final_term_ids, $taxonomy );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Now handle the product attributes structure.
		$product_attributes = $product->get_attributes();
		$updated_attributes = array();

		// Keep existing attributes (except the one we're updating).
		$attribute_key = sanitize_title( $taxonomy );
		foreach ( $product_attributes as $key => $attribute ) {
			if ( $key !== $attribute_key ) {
				$updated_attributes[ $key ] = $attribute;
			}
		}

		// Create the new/updated attribute.
		$attribute = new \WC_Product_Attribute();
		$attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_name ) );
		$attribute->set_name( $taxonomy );
		$attribute->set_options( $final_term_ids );
		$attribute->set_visible( true );
		$attribute->set_variation( false );

		// Add it to the attributes array.
		$updated_attributes[ $attribute_key ] = $attribute;

		// Set all attributes on product and save.
		$product->set_attributes( $updated_attributes );
		$product->save();

		return true;
	}
}
