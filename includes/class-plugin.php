<?php
/**
 * Main plugin class.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Main Plugin class.
 *
 * Coordinates plugin initialization and manages dependencies.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private string $version;

	/**
	 * Admin hooks instance.
	 *
	 * @var Admin_Hooks|null
	 */
	private ?Admin_Hooks $admin_hooks = null;

	/**
	 * AJAX handler instance.
	 *
	 * @var Ajax_Handler|null
	 */
	private ?Ajax_Handler $ajax_handler = null;

	/**
	 * Attribute processor instance.
	 *
	 * @var Attribute_Processor|null
	 */
	private ?Attribute_Processor $attribute_processor = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->version = BPAA_VERSION;
	}

	/**
	 * Run the plugin.
	 *
	 * @since 1.0.0
	 */
	public function run(): void {
		// Load text domain for translations.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Register admin hooks if in admin.
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this->get_admin_hooks(), 'add_menu_items' ) );
			add_action( 'admin_enqueue_scripts', array( $this->get_admin_hooks(), 'enqueue_assets' ) );
		}

		// Register AJAX handlers.
		add_action( 'wp_ajax_' . AJAX_ACTION_PROCESS_BATCH, array( $this->get_ajax_handler(), 'process_batch' ) );
		add_action( 'wp_ajax_' . AJAX_ACTION_GET_TERMS, array( $this->get_ajax_handler(), 'get_terms' ) );
	}

	/**
	 * Load plugin text domain for translations.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain(
			'bulk-product-attribute-assign',
			false,
			dirname( plugin_basename( BPAA_FILE ) ) . '/languages'
		);
	}

	/**
	 * Get admin hooks instance (lazy loading).
	 *
	 * @since 1.0.0
	 *
	 * @return Admin_Hooks Admin hooks instance.
	 */
	public function get_admin_hooks(): Admin_Hooks {
		if ( is_null( $this->admin_hooks ) ) {
			$this->admin_hooks = new Admin_Hooks( $this->version );
		}
		return $this->admin_hooks;
	}

	/**
	 * Get AJAX handler instance (lazy loading).
	 *
	 * @since 1.0.0
	 *
	 * @return Ajax_Handler AJAX handler instance.
	 */
	public function get_ajax_handler(): Ajax_Handler {
		if ( is_null( $this->ajax_handler ) ) {
			$this->ajax_handler = new Ajax_Handler( $this->get_attribute_processor() );
		}
		return $this->ajax_handler;
	}

	/**
	 * Get attribute processor instance (lazy loading).
	 *
	 * @since 1.0.0
	 *
	 * @return Attribute_Processor Attribute processor instance.
	 */
	public function get_attribute_processor(): Attribute_Processor {
		if ( is_null( $this->attribute_processor ) ) {
			$this->attribute_processor = new Attribute_Processor();
		}
		return $this->attribute_processor;
	}

	/**
	 * Get plugin version.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin version.
	 */
	public function get_version(): string {
		return $this->version;
	}
}
