# Bulk Assign Product Attributes

**Contributors:** Paul Faulkner  
**Requires at least:** WordPress 6.0  
**Tested up to:** WordPress 6.7  
**Requires PHP:** 8.0  
**Requires Plugins:** WooCommerce  
**WC requires at least:** 7.0  
**WC tested up to:** 10.0  
**Stable tag:** 0.1.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Bulk-assign product attribute terms to WooCommerce products. Designed for setting customs metadata across large product catalogs.

---

## Description

This WordPress/WooCommerce plugin provides a simple interface for bulk-assigning product attribute terms to products. It was created to help store owners set customs and import metadata (Country of Origin, HS Code, Customs Description) across hundreds or thousands of products efficiently.

### Features

- **Bulk Operations:** Process hundreds or thousands of products at once
- **AJAX Batch Processing:** Handles large datasets without timeouts
- **Real-time Progress:** Live progress bar and statistics during processing
- **Two Modes:**
  - **Add Mode:** Add new terms to existing attribute terms
  - **Replace Mode:** Replace all existing terms with new ones
- **WooCommerce HPOS Compatible:** Fully compatible with High-Performance Order Storage
- **Safe Processing:** Confirmation dialogs and comprehensive error reporting

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
2. **Select Terms:** Pick one or more terms to assign
3. **Choose Mode:**
   - **Add:** Adds selected terms to existing terms
   - **Replace:** Replaces all existing terms with selected ones
4. **Click "Set attributes now..."**
5. **Confirm:** Confirm the operation (cannot be undone)
6. **Monitor Progress:** Watch real-time progress and statistics

### Important Notes

- Operations cannot be undone
- Only shippable products are processed
- For variable products, attributes are set on the parent product only
- Bundle products are skipped by default

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
