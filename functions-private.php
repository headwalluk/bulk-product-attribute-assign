<?php
/**
 * Private functions for the Bulk Product Attribute Assign plugin.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Get the plugin instance.
 *
 * @since 1.0.0
 *
 * @return Plugin Plugin instance.
 */
function get_plugin_instance(): Plugin {
	global $bulk_product_attribute_assign_plugin;
	return $bulk_product_attribute_assign_plugin;
}
