# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Planned
- Product category/tag filtering (query builder UI)
- Bundle product handling option
- Resume capability for interrupted operations
- Export results to CSV

---

## [1.1.0] - 2026-01-13

### Added
- **Attribute Visibility Control:** New checkbox option to control whether attributes are visible on product pages (Additional Information tab)
- **UI Enhancement:** "Attribute visible on product page" option added as first checkbox in Options section

### Changed
- Attribute visibility now defaults to hidden (unchecked) to prevent clutter on product pages
- Users can opt-in to showing attributes on frontend by checking the new option

---

## [1.0.0] - 2026-01-13

### Added
- **Admin Interface:** Full-featured admin page under WooCommerce → Bulk Assign Attributes
- **Attribute Selection:** SelectWoo dropdown for choosing product attributes
- **Term Selection:** Multi-select dropdown for choosing terms (loads dynamically via AJAX)
- **Processing Modes:**
  - Add mode: Add terms to existing product attributes
  - Replace mode: Replace all existing terms with selected ones
- **Product Filtering:** Optional checkbox to include virtual/downloadable products
- **Dry-Run Mode:** Preview mode to test operations without making changes
- **Product Count Preview:** Shows "This will affect X products" before processing
- **AJAX Batch Processing:** Dynamic batch sizing (ceil(total/3), capped at 50 products)
- **Real-Time Progress:** Live progress bar with accurate percentage and product count
- **Error Reporting:** Detailed error messages with product ID and failure reason
- **Results Display:** Summary showing successful/failed operations
- **WooCommerce HPOS Support:** Full compatibility with High-Performance Order Storage
- **Cache Suspension:** Prevents object cache saturation with large datasets
- **Security:** Nonce verification and capability checks on all endpoints
- **Filterable Capability:** `bpaa_required_capability` filter (default: `manage_woocommerce`)
- **Filterable Batch Size:** `bpaa_batch_size` filter for server-specific tuning

### Technical
- **Admin Hooks:** Menu registration, asset enqueuing (SelectWoo, admin CSS/JS)
- **AJAX Handler:** 4 endpoints - process_batch, get_terms, get_product_count, get_total_count
- **Attribute Processor:** Core logic for product queries and attribute assignment
- **WC_Product_Attribute:** Proper attribute structure for WooCommerce compatibility
- **Parent Products Only:** Variations inherit attributes from parent (prevents errors)
- **Input Sanitization:** All POST data properly sanitized and validated
- **Output Escaping:** All template output properly escaped
- **Error Handling:** WP_Error objects with translatable messages
- **Performance:** wp_suspend_cache_addition() during large queries and batch loops
- **Code Quality:** 100% WordPress Coding Standards compliant (phpcs)
- **Translation Ready:** All strings translatable with proper text domain

### Security
- ✅ Nonce verification on all AJAX endpoints
- ✅ Capability checks on admin page and all AJAX handlers
- ✅ Input sanitization: sanitize_text_field(), absint(), filter_var()
- ✅ Output escaping: esc_html(), esc_attr(), esc_html_e()
- ✅ Data validation: empty checks, whitelist validation, taxonomy/term existence
- ✅ No direct SQL queries (uses WordPress/WooCommerce APIs)
- ✅ HPOS compatible (uses WC_Product CRUD methods)

### Fixed
- SelectWoo styling issues (switched from Select2 to WooCommerce's SelectWoo)
- Product count returning 0 (removed unnecessary attribute check)
- WC_Product_Attribute conversion error (rebuilt attributes array properly)
- Variations causing errors (only process parent products)
- Progress bar element IDs missing (added IDs to template)
- Progress bar showing 100% from previous run (reset in showProgress)

---

## [0.1.0] - 2026-01-13

### Added
- Initial plugin scaffold
- Plugin foundation with HPOS compatibility
- Main plugin class with lazy instantiation
- Constants file for plugin-wide constants
- Admin hooks placeholder class
- AJAX handler placeholder class
- Attribute processor placeholder class
- WordPress coding standards configuration (phpcs.xml)
- Project documentation structure
- Requirements documentation
- Project tracker with milestones
- Translation support setup

### Technical
- PHP 8.0+ type hints and return types
- WordPress coding standards compliance
- WooCommerce HPOS compatibility declaration
- Plugin dependency declaration (requires WooCommerce)
- Proper namespace structure (Bulk_Product_Attribute_Assign)
- Lazy loading pattern for class instantiation

---

## Version History

- **1.0.0** - First stable release with full feature set (2026-01-13)
- **0.1.0** - Foundation and scaffolding (2026-01-13)

---

[Unreleased]: https://github.com/yourusername/bulk-product-attribute-assign/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/yourusername/bulk-product-attribute-assign/releases/tag/v0.1.0
