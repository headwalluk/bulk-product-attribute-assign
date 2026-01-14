=== Bulk Assign Product Attributes ===
Contributors: paulfaulkner
Tags: woocommerce, bulk, attributes, products, customs
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Requires Plugins: woocommerce
WC requires at least: 7.0
WC tested up to: 10.0
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Bulk-assign product attribute terms to WooCommerce products with advanced filtering. Designed for setting customs metadata across large product catalogs.

== Description ==

This WordPress/WooCommerce plugin provides a simple interface for bulk-assigning product attribute terms to products. It was created to help store owners set customs and import metadata (Country of Origin, HS Code, Customs Description) across hundreds or thousands of products efficiently.

= Features =

* **Bulk Operations:** Process hundreds or thousands of products at once
* **Advanced Product Filtering:** Filter by status, categories, tags, and product name
* **AJAX Batch Processing:** Handles large datasets without timeouts with dynamic batch sizing
* **Real-time Progress:** Live progress bar with accurate percentage and product count
* **Product Count Preview:** Shows "This will affect X products" with live filter updates
* **Two Modes:** Add new terms or replace existing terms
* **Product Filtering Options:** Include/exclude virtual and downloadable products
* **Dry-Run Mode:** Preview mode to test operations without making changes
* **Detailed Error Reporting:** See exactly which products failed and why
* **WooCommerce HPOS Compatible:** Fully compatible with High-Performance Order Storage
* **Safe Processing:** Confirmation dialogs and comprehensive error reporting
* **Performance Optimized:** Cache suspension for large datasets prevents server overload
* **Security First:** Nonce verification, capability checks, input sanitization, output escaping

= Use Cases =

* Setting Country of Origin for customs compliance
* Assigning HS Codes (commodity codes) to products
* Adding customs descriptions for international shipping
* Bulk-updating any product attribute across your catalog

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/bulk-product-attribute-assign/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to **WooCommerce → Tools → Bulk Assign Product Attributes**

== Frequently Asked Questions ==

= Does this work with WooCommerce HPOS? =

Yes! This plugin is fully compatible with WooCommerce High-Performance Order Storage (HPOS).

= Can I undo bulk operations? =

No. Operations cannot be undone. The plugin will show a confirmation dialog before processing.

= What types of products are supported? =

Simple and variable products are supported. For variable products, attributes are set on the parent product only - variations inherit attributes automatically.

= Does this work with custom attributes? =

Yes! The plugin works with any product attribute registered as a global taxonomy.

= Will this timeout with large product catalogs? =

No. The plugin uses AJAX batch processing with dynamic batch sizing to handle large datasets without timeouts. It also suspends cache addition during processing to prevent object cache saturation.

= Can I preview changes before applying them? =

Yes! Check the "Preview only (dry-run)" option to see what would happen without actually making changes.

= How do I change the required user capability? =

Use the `bpaa_required_capability` filter:

`add_filter( 'bpaa_required_capability', function() { return 'manage_options'; } );`

== Screenshots ==

1. Main bulk assign interface (coming soon)
2. Progress bar during processing (coming soon)
3. Results summary (coming soon)

== Changelog ==

= 1.1.0 - 2026-01-13 =
* Added: Attribute visibility control - new checkbox to control whether attributes appear on product pages
* Changed: Attribute visibility now defaults to hidden to prevent clutter on product pages

= 1.0.0 - 2026-01-13 =
* First stable release
* Full admin interface under WooCommerce → Bulk Assign Attributes
* SelectWoo dropdowns for attribute and term selection
* AJAX batch processing with dynamic batch sizing
* Real-time progress bar with accurate percentage
* Product count preview before processing
* Two modes: Add terms or Replace all terms
* Optional virtual/downloadable product filtering
* Dry-run mode for previewing changes
* Detailed error reporting with product IDs
* WooCommerce HPOS fully compatible
* Cache suspension for large datasets
* Complete security implementation (nonces, capabilities, sanitization, escaping)
* 100% WordPress Coding Standards compliant
* Full translation support

= 0.1.0 - 2026-01-13 =
* Initial release
* Plugin foundation and scaffolding
* WooCommerce HPOS compatibility
* WordPress coding standards compliance

== Upgrade Notice ==

= 1.0.0 =
First stable release with full feature set. Upgrade from 0.1.0 to get complete bulk attribute assignment functionality.

== Requirements ==

* WordPress 6.0 or higher
* PHP 8.0 or higher
* WooCommerce 7.0 or higher
* Product attributes must be registered as global taxonomies

== Development ==

This plugin follows WordPress Coding Standards and modern PHP practices (PHP 8.0+ type hints, namespaces, etc.).

Development documentation can be found in the `dev-notes/` directory of the plugin.

== Support ==

For issues and feature requests, please use the GitHub issue tracker or contact the plugin author.
