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
		// TODO: Implement admin menu registration.
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		// TODO: Implement conditional asset enqueuing.
	}
}
