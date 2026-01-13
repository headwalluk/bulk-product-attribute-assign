# Bulk Assign Product Attributes

**Contributors:** Paul Faulkner  
**Requires at least:** WordPress 6.0  
**Tested up to:** WordPress 6.7  
**Requires PHP:** 8.0  
**Requires Plugins:** WooCommerce  
**WC requires at least:** 7.0  
**WC tested up to:** 10.0  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Bulk-assign product attribute terms to WooCommerce products. Designed for setting customs metadata across large product catalogs.

---

## Description

This WordPress/WooCommerce plugin provides a simple interface for bulk-assigning product attribute terms to products. It was created to help store owners set customs and import metadata (Country of Origin, HS Code, Customs Description) across hundreds or thousands of products efficiently.

### Features

- **Bulk Operations:** Process hundreds or thousands of products at once
- **AJAX Batch Processing:** Handles large datasets without timeouts with dynamic batch sizing
- **Real-time Progress:** Live progress bar with accurate percentage and product count
- **Product Count Preview:** Shows "This will affect X products" before processing
- **Two Modes:**
  - **Add Mode:** Add new terms to existing attribute terms
  - **Replace Mode:** Replace all existing terms with new ones
- **Product Filtering:** Include/exclude virtual and downloadable products
- **Dry-Run Mode:** Preview mode to test operations without making changes
- **Detailed Error Reporting:** See exactly which products failed and why
- **WooCommerce HPOS Compatible:** Fully compatible with High-Performance Order Storage
- **Safe Processing:** Confirmation dialogs and comprehensive error reporting
- **Performance Optimized:** Cache suspension for large datasets prevents server overload
- **Security First:** Nonce verification, capability checks, input sanitization, output escaping

### Use Cases

- Setting Country of Origin for customs compliance
- Assigning HS Codes (commodity codes) to products
- Adding customs descriptions for international shipping
- Bulk-updating any product attribute across your catalog

---

## Installation

1. Upload the plugin files to `/wp-content/plugins/bulk-product-attribute-assign/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to **WooCommerce → Tools → Bulk Assign Product Attributes**

---

## Usage

1. **Select Attribute:** Choose the product attribute you want to update
2. **Select Terms:** Pick one or more terms to assign (loads dynamically)
3. **Choose Mode:**
   - **Add:** Adds selected terms to existing terms
   - **Replace:** Replaces all existing terms with selected ones
4. **Optional Settings:**
   - **Include virtual/downloadable products:** Check to process non-shippable products
   - **Preview only (dry-run):** Check to test without making changes
5. **Preview Count:** See how many products will be affected
6. **Click "Set attributes now..."**
7. **Confirm:** Confirm the operation (cannot be undone)
8. **Monitor Progress:** Watch real-time progress bar and statistics
9. **Review Results:** See successful/failed operations with details

### Important Notes

- Operations cannot be undone (use dry-run mode to preview)
- By default, only shippable products are processed
- For variable products, attributes are set on the parent product only (variations inherit)
- Attributes must be registered as global product taxonomies
- Progress bar shows accurate percentage and product count
- Failed operations display product ID and error reason

---

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- WooCommerce 7.0 or higher
- Product attributes must be registered as global taxonomies

---

## Development

### File Structure

```
bulk-product-attribute-assign/
├── bulk-product-attribute-assign.php  # Main plugin file
├── constants.php                      # Plugin constants
├── includes/                          # Core classes
│   ├── class-plugin.php
│   ├── class-admin-hooks.php
│   ├── class-ajax-handler.php
│   └── class-attribute-processor.php
├── admin-templates/                   # Admin page templates
├── assets/admin/                      # Admin CSS/JS
├── dev-notes/                         # Development documentation
└── languages/                         # Translation files
```

### Coding Standards

This plugin follows WordPress Coding Standards. Run checks with:

```bash
phpcs              # Check standards
phpcbf             # Auto-fix issues
```

### Development Notes

See `dev-notes/` directory for:
- Requirements documentation
- Project tracker and milestones
- Implementation patterns
- Coding standards and workflows

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for detailed version history.

---

## Support

For issues and feature requests, please use the GitHub issue tracker.

---

## License

This plugin is licensed under the GPLv2 or later.
