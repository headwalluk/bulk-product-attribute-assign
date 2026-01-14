<?php
/**
 * AJAX request handler.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Ajax Handler class.
 *
 * Handles AJAX requests for batch processing and term retrieval.
 *
 * @since 1.0.0
 */
class Ajax_Handler {
	/**
	 * Attribute processor instance.
	 *
	 * @var Attribute_Processor
	 */
	private Attribute_Processor $attribute_processor;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Attribute_Processor $attribute_processor Attribute processor instance.
	 */
	public function __construct( Attribute_Processor $attribute_processor ) {
		$this->attribute_processor = $attribute_processor;

		// Register AJAX handlers.
		add_action( 'wp_ajax_' . AJAX_ACTION_PROCESS_BATCH, array( $this, 'process_batch' ) );
		add_action( 'wp_ajax_' . AJAX_ACTION_GET_TERMS, array( $this, 'get_terms' ) );
		add_action( 'wp_ajax_' . AJAX_ACTION_GET_PRODUCT_COUNT, array( $this, 'get_product_count' ) );
		add_action( 'wp_ajax_' . AJAX_ACTION_GET_TOTAL_COUNT, array( $this, 'get_total_count' ) );
	}

	/**
	 * Extract and sanitize filter values from POST data.
	 *
	 * @since 1.2.0
	 *
	 * @return array<string, mixed> Sanitized filter values.
	 */
	private function extract_filters(): array {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in calling method.
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized in array_map calls below.
		$filters = isset( $_POST['filters'] ) && is_array( $_POST['filters'] ) ? wp_unslash( $_POST['filters'] ) : array();
		// phpcs:enable

		$enabled = isset( $filters['enabled'] ) ? (bool) filter_var( $filters['enabled'], FILTER_VALIDATE_BOOLEAN ) : false;

		// If filters are not enabled, return defaults.
		if ( ! $enabled ) {
			return array(
				'product_status'       => array( 'publish' ),
				'categories'           => array(),
				'tags'                 => array(),
				'name_search'          => '',
				'include_virtual'      => false,
				'include_downloadable' => false,
			);
		}

		// Filters are enabled - extract and sanitize values.
		return array(
			'product_status'       => isset( $filters['product_status'] ) && is_array( $filters['product_status'] ) ? array_map( 'sanitize_text_field', $filters['product_status'] ) : array( 'publish' ),
			'categories'           => isset( $filters['categories'] ) && is_array( $filters['categories'] ) ? array_map( 'absint', $filters['categories'] ) : array(),
			'tags'                 => isset( $filters['tags'] ) && is_array( $filters['tags'] ) ? array_map( 'absint', $filters['tags'] ) : array(),
			'name_search'          => isset( $filters['name_search'] ) ? sanitize_text_field( $filters['name_search'] ) : '',
			'include_virtual'      => isset( $filters['include_virtual'] ) ? (bool) filter_var( $filters['include_virtual'], FILTER_VALIDATE_BOOLEAN ) : false,
			'include_downloadable' => isset( $filters['include_downloadable'] ) ? (bool) filter_var( $filters['include_downloadable'], FILTER_VALIDATE_BOOLEAN ) : false,
		);
	}

	/**
	 * Get total count of products to process (before starting batch processing).
	 *
	 * @since 1.0.0
	 */
	public function get_total_count(): void {
		check_ajax_referer( NONCE_ACTION_GET_TOTAL_COUNT, 'nonce' );

		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		if ( ! current_user_can( $required_capability ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'bulk-product-attribute-assign' ) ) );
		}

		$filters = $this->extract_filters();

		// Get total count.
		$product_ids = $this->attribute_processor->get_products_to_process( $filters );

		$total_count = count( $product_ids );

		// Calculate optimal batch size: ceil(total / 3), capped at DEF_BATCH_SIZE.
		// Ensure batch size is at least 1 when we have products to process.
		$batch_size = $total_count > 0 ? max( 1, (int) ceil( $total_count / 3 ) ) : DEF_BATCH_SIZE;
		$batch_size = min( $batch_size, DEF_BATCH_SIZE );

		// Apply filter to allow customization.
		$batch_size = apply_filters( 'bpaa_batch_size', $batch_size, $total_count );

		wp_send_json_success(
			array(
				'total_count' => $total_count,
				'batch_size'  => $batch_size,
			)
		);
	}

	/**
	 * Handle batch processing AJAX request.
	 *
	 * @since 1.0.0
	 */
	public function process_batch(): void {
		check_ajax_referer( NONCE_ACTION_PROCESS, 'nonce' );

		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		if ( ! current_user_can( $required_capability ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'bulk-product-attribute-assign' ) ) );
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		$attribute         = isset( $_POST['attribute'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute'] ) ) : '';
		$term_ids          = isset( $_POST['term_ids'] ) && is_array( $_POST['term_ids'] ) ? array_map( 'absint', wp_unslash( $_POST['term_ids'] ) ) : array();
		$mode              = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : DEF_MODE_ADD;
		$attribute_visible = isset( $_POST['attribute_visible'] ) ? (bool) filter_var( wp_unslash( $_POST['attribute_visible'] ), FILTER_VALIDATE_BOOLEAN ) : false;
		$dry_run           = isset( $_POST['dry_run'] ) ? (bool) filter_var( wp_unslash( $_POST['dry_run'] ), FILTER_VALIDATE_BOOLEAN ) : false;
		$offset            = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
		$batch_size        = isset( $_POST['batch_size'] ) ? absint( $_POST['batch_size'] ) : DEF_BATCH_SIZE;
		// phpcs:enable

		$filters = $this->extract_filters();

		// Validate inputs.
		if ( empty( $attribute ) ) {
			wp_send_json_error( array( 'message' => __( 'Attribute is required.', 'bulk-product-attribute-assign' ) ) );
		}

		if ( empty( $term_ids ) ) {
			wp_send_json_error( array( 'message' => __( 'At least one term is required.', 'bulk-product-attribute-assign' ) ) );
		}

		if ( ! in_array( $mode, array( DEF_MODE_ADD, DEF_MODE_REPLACE ), true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid mode.', 'bulk-product-attribute-assign' ) ) );
		}

		// Get batch of products to process.
		$filters['limit']  = $batch_size;
		$filters['offset'] = $offset;

		$product_ids = $this->attribute_processor->get_products_to_process( $filters );

		// Track results.
		$results = array(
			'processed'   => 0,
			'successful'  => 0,
			'failed'      => 0,
			'errors'      => array(),
			'product_ids' => array(),
			'has_more'    => false,
			'next_offset' => $offset,
		);

		// Suspend cache addition during batch processing to prevent object cache saturation.
		wp_suspend_cache_addition( true );

		// Process each product.
		foreach ( $product_ids as $product_id ) {
			++$results['processed'];

			$result = $this->attribute_processor->process_single_product(
				$product_id,
				$attribute,
				$term_ids,
				$mode,
				$dry_run,
				$attribute_visible
			);

			if ( is_wp_error( $result ) ) {
				++$results['failed'];
				$results['errors'][] = array(
					'product_id' => $product_id,
					'message'    => $result->get_error_message(),
				);
			} else {
				++$results['successful'];
				$results['product_ids'][] = $product_id;
			}
		}

		// Resume cache addition.
		wp_suspend_cache_addition( false );

		// Check if there are more products to process.
		$next_offset       = $offset + $batch_size;
		$filters['limit']  = 1;
		$filters['offset'] = $next_offset;
		$more_products     = $this->attribute_processor->get_products_to_process( $filters );

		$results['has_more']    = ! empty( $more_products );
		$results['next_offset'] = $next_offset;

		wp_send_json_success( $results );
	}

	/**
	 * Get count of products that will be processed.
	 *
	 * @since 1.0.0
	 */
	public function get_product_count(): void {
		check_ajax_referer( NONCE_ACTION_GET_PRODUCT_COUNT, 'nonce' );

		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		if ( ! current_user_can( $required_capability ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'bulk-product-attribute-assign' ) ) );
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		$filters = $this->extract_filters();

		// Get product IDs using filter.
		$product_ids = $this->attribute_processor->get_products_to_process( $filters );
		$count       = count( $product_ids );

		wp_send_json_success( array( 'count' => $count ) );
	}

	/**
	 * Handle get terms AJAX request.
	 *
	 * @since 1.0.0
	 */
	public function get_terms(): void {
		// Verify nonce.
		check_ajax_referer( NONCE_ACTION_GET_TERMS, 'nonce' );

		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		// Check user capabilities.
		if ( ! current_user_can( $required_capability ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Insufficient permissions.', 'bulk-product-attribute-assign' ),
				)
			);
		}

		// Get attribute name from request.
		$attribute_name = isset( $_POST['attribute'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute'] ) ) : '';

		if ( empty( $attribute_name ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Attribute name is required.', 'bulk-product-attribute-assign' ),
				)
			);
		}

		// Get taxonomy name (WooCommerce attributes use pa_ prefix).
		$taxonomy = wc_attribute_taxonomy_name( $attribute_name );

		// Check if taxonomy exists.
		if ( ! taxonomy_exists( $taxonomy ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Attribute taxonomy does not exist.', 'bulk-product-attribute-assign' ),
				)
			);
		}

		// Suspend cache addition for potentially large term lists.
		wp_suspend_cache_addition( true );

		// Get terms for this attribute.
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		// Resume cache addition.
		wp_suspend_cache_addition( false );

		if ( is_wp_error( $terms ) ) {
			wp_send_json_error(
				array(
					'message' => $terms->get_error_message(),
				)
			);
		}

		// Format terms for Select2.
		$formatted_terms = array();
		foreach ( $terms as $term ) {
			$formatted_terms[] = array(
				'id'   => $term->term_id,
				'text' => $term->name,
			);
		}

		wp_send_json_success(
			array(
				'terms' => $formatted_terms,
			)
		);
	}
}
