<?php
/**
 * Admin hooks handler.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Admin Hooks class.
 *
 * Handles admin menu registration and asset enqueuing.
 *
 * @since 1.0.0
 */
class Admin_Hooks {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private string $version;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version Plugin version.
	 */
	public function __construct( string $version ) {
		$this->version = $version;
	}

	/**
	 * Add admin menu items.
	 *
	 * @since 1.0.0
	 */
	public function add_menu_items(): void {
		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		add_submenu_page(
			'woocommerce',
			__( 'Bulk Assign Product Attributes', 'bulk-product-attribute-assign' ),
			__( 'Bulk Assign Attributes', 'bulk-product-attribute-assign' ),
			$required_capability,
			MENU_SLUG,
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render_admin_page(): void {
		$required_capability = apply_filters( 'bpaa_required_capability', REQUIRED_CAPABILITY );

		// Check user capabilities.
		if ( ! current_user_can( $required_capability ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'bulk-product-attribute-assign' ) );
		}

		// Load the template.
		require_once BPAA_PATH . 'admin-templates/bulk-assign-page.php';
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		// Only load on our plugin page.
		if ( 'woocommerce_page_' . MENU_SLUG !== $hook_suffix ) {
			return;
		}

		// Enqueue WooCommerce's enhanced Select2 (SelectWoo).
		wp_enqueue_script( 'selectWoo' );

		// Also enqueue WooCommerce admin styles for consistency.
		wp_enqueue_style( 'woocommerce_admin_styles' );

		// Enqueue our admin CSS.
		wp_enqueue_style(
			'bpaa-admin',
			BPAA_URL . 'assets/admin/bulk-assign.css',
			array(),
			$this->version
		);

		// Enqueue our admin JavaScript.
		wp_enqueue_script(
			'bpaa-admin',
			BPAA_URL . 'assets/admin/bulk-assign.js',
			array( 'jquery', 'selectWoo' ),
			$this->version,
			true
		);

		// Localize script with data for AJAX.
		wp_localize_script(
			'bpaa-admin',
			'bpaaAdmin',
			array(
				'ajaxUrl'               => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( NONCE_ACTION_PROCESS ),
				'termsNonce'            => wp_create_nonce( NONCE_ACTION_GET_TERMS ),
				'productCountNonce'     => wp_create_nonce( NONCE_ACTION_GET_PRODUCT_COUNT ),
				'totalCountNonce'       => wp_create_nonce( NONCE_ACTION_GET_TOTAL_COUNT ),
				'processBatchAction'    => AJAX_ACTION_PROCESS_BATCH,
				'getTermsAction'        => AJAX_ACTION_GET_TERMS,
				'getProductCountAction' => AJAX_ACTION_GET_PRODUCT_COUNT,
				'getTotalCountAction'   => AJAX_ACTION_GET_TOTAL_COUNT,
				'strings'               => array(
					'selectAttribute' => __( 'Select an attribute...', 'bulk-product-attribute-assign' ),
					'selectTerms'     => __( 'Select terms...', 'bulk-product-attribute-assign' ),
					'confirmMessage'  => __( 'This operation cannot be undone. Are you sure you want to proceed?', 'bulk-product-attribute-assign' ),
					'processing'      => __( 'Processing...', 'bulk-product-attribute-assign' ),
					'complete'        => __( 'Complete!', 'bulk-product-attribute-assign' ),
					'error'           => __( 'An error occurred.', 'bulk-product-attribute-assign' ),
				),
			)
		);
	}
}
