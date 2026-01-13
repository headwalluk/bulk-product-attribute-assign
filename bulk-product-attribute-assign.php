<?php
/**
 * Plugin Name: Bulk Assign Product Attributes
 * Plugin URI: https://headwall-hosting.com/
 * Description: Bulk-assign product attribute terms to WooCommerce products. Designed for setting customs metadata (Country of Origin, HS Code, Customs Description) across large product catalogs.
 * Version: 1.0.0
 * Author: Paul Faulkner
 * Author URI: https://headwall-hosting.com/
 * Text Domain: bulk-product-attribute-assign
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Requires Plugins: woocommerce
 * WC requires at least: 7.0
 * WC tested up to: 10.0
 *
 * @package Bulk_Product_Attribute_Assign
 */

defined( 'ABSPATH' ) || die();

// Define plugin constants.
define( 'BPAA_VERSION', '1.0.0' );
define( 'BPAA_FILE', __FILE__ );
define( 'BPAA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BPAA_URL', plugin_dir_url( __FILE__ ) );

// Load plugin constants.
require_once BPAA_PATH . 'constants.php';

// Load main plugin class.
require_once BPAA_PATH . 'includes/class-plugin.php';
require_once BPAA_PATH . 'includes/class-admin-hooks.php';
require_once BPAA_PATH . 'includes/class-ajax-handler.php';
require_once BPAA_PATH . 'includes/class-attribute-processor.php';

/**
 * Declare WooCommerce HPOS compatibility.
 *
 * @since 1.0.0
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'custom_order_tables',
				__FILE__,
				true
			);
		}
	}
);

/**
 * Initialize the plugin.
 *
 * @since 1.0.0
 */
function bulk_product_attribute_assign_init_plugin(): void {
	// Check if WooCommerce is active.
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'bulk_product_attribute_assign_woocommerce_missing_notice' );
		return;
	}

	global $bulk_product_attribute_assign_plugin;
	$bulk_product_attribute_assign_plugin = new \Bulk_Product_Attribute_Assign\Plugin();
	$bulk_product_attribute_assign_plugin->run();
}
add_action( 'plugins_loaded', 'bulk_product_attribute_assign_init_plugin' );

/**
 * Display admin notice if WooCommerce is not active.
 *
 * @since 1.0.0
 */
function bulk_product_attribute_assign_woocommerce_missing_notice(): void {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'Bulk Assign Product Attributes requires WooCommerce to be installed and active.', 'bulk-product-attribute-assign' )
	);
}
