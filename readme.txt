=== Bulk Assign Product Attributes ===
Contributors: paulfaulkner
Tags: woocommerce, bulk, attributes, products, customs
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Requires Plugins: woocommerce
WC requires at least: 7.0
WC tested up to: 10.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Bulk-assign product attribute terms to WooCommerce products. Designed for setting customs metadata across large product catalogs.

== Description ==

This WordPress/WooCommerce plugin provides a simple interface for bulk-assigning product attribute terms to products. It was created to help store owners set customs and import metadata (Country of Origin, HS Code, Customs Description) across hundreds or thousands of products efficiently.

= Features =

* **Bulk Operations:** Process hundreds or thousands of products at once
* **AJAX Batch Processing:** Handles large datasets without timeouts
* **Real-time Progress:** Live progress bar and statistics during processing
* **Two Modes:** Add new terms or replace existing terms
* **WooCommerce HPOS Compatible:** Fully compatible with High-Performance Order Storage
* **Safe Processing:** Confirmation dialogs and comprehensive error reporting

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

Simple and variable products are supported. For variable products, attributes are set on the parent product only. Bundle products are skipped by default.

= Does this work with custom attributes? =

Yes! The plugin works with any product attribute registered as a global taxonomy.

= Will this timeout with large product catalogs? =

No. The plugin uses AJAX batch processing to handle large datasets without timeouts.

== Screenshots ==

1. Main bulk assign interface (coming soon)
2. Progress bar during processing (coming soon)
3. Results summary (coming soon)

== Changelog ==

= 0.1.0 - 2026-01-13 =
* Initial release
* Plugin foundation and scaffolding
* WooCommerce HPOS compatibility
* WordPress coding standards compliance

== Upgrade Notice ==

= 0.1.0 =
Initial release - foundation and scaffolding complete.

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
