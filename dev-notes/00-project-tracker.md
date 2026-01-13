# Bulk Assign Product Attributes - Project Tracker

**Version:** 0.1.0  
**Last Updated:** 13 January 2026

---

## Overview

WordPress/WooCommerce plugin to bulk-assign product attribute terms to products. Created to help client set customs metadata (Country of Origin, HS Code, Customs Description) across 1,000+ products for international shipping compliance.

**Plugin Slug:** `bulk-product-attribute-assign`  
**Plugin Name:** Bulk Assign Product Attributes  
**Admin Location:** WooCommerce → Tools menu

---

## Milestones

### ✅ Milestone 0: Planning & Requirements
- [x] Create requirements document
- [x] Clarify variation handling approach
- [x] Define Phase 1 vs Phase 2 scope
- [x] Review WordPress coding standards
- [x] Create project tracker

### ✅ Milestone 1: Plugin Foundation
- [x] Create main plugin file with headers
- [x] Create constants.php with plugin constants
- [x] Create includes/class-plugin.php (main plugin class)
- [x] Declare WooCommerce HPOS compatibility
- [x] Create phpcs.xml configuration
- [x] Set up proper file structure (includes/, admin-templates/, assets/)
- [x] Add translation support setup
- [x] Test plugin activation/deactivation
- [x] Create placeholder class files
- [x] Create README.md, CHANGELOG.md, readme.txt
- [x] Run phpcs and fix violations
- [x] Commit foundation to Git

### 🔄 Milestone 2: Admin Integration
- [ ] Create includes/class-admin-hooks.php
- [ ] Register admin menu under WooCommerce → Tools
- [ ] Create admin-templates/bulk-assign-page.php
- [ ] Implement lazy loading for admin hooks
- [ ] Enqueue admin assets (CSS/JS) conditionally
- [ ] Add capability checks (manage_woocommerce)
- [ ] Test menu appears in correct location

### 🔄 Milestone 3: Admin UI - Basic Form
- [ ] Create attribute selection dropdown
- [ ] Load product attributes dynamically (global taxonomies)
- [ ] Create term selection (multi-select or checkboxes)
- [ ] Add mode selector (Add/Replace radio buttons)
- [ ] Add "Set attributes now" button
- [ ] Add nonce field for security
- [ ] Style with WordPress admin styles
- [ ] Test form renders correctly

### 🔄 Milestone 4: JavaScript & AJAX Setup
- [ ] Create assets/admin/bulk-assign.js
- [ ] Create assets/admin/bulk-assign.css
- [ ] Implement confirmation dialog on submit
- [ ] Set up AJAX request to backend
- [ ] Create includes/class-ajax-handler.php
- [ ] Register AJAX actions (admin only)
- [ ] Pass nonce and AJAX URL to JavaScript
- [ ] Test AJAX communication works

### 🔄 Milestone 5: Core Processing Logic
- [ ] Create includes/class-attribute-processor.php
- [ ] Implement get_products_to_process() method
- [ ] Implement process_single_product() method
- [ ] Handle simple products correctly
- [ ] Handle variable products (parent only, set_variation(false))
- [ ] Skip non-shippable products ($product->needs_shipping())
- [ ] Add existing attributes vs create new attributes logic
- [ ] Implement "Add" mode (add terms to existing)
- [ ] Implement "Replace" mode (replace all terms)
- [ ] Test with various product types

### 🔄 Milestone 6: Batch Processing
- [ ] Implement batch processing in AJAX handler
- [ ] Process products in chunks (50 per batch)
- [ ] Track progress across batches
- [ ] Return batch results to frontend
- [ ] Implement wp_suspend_cache_addition() wrapper
- [ ] Handle timeouts gracefully
- [ ] Test with larger product sets

### 🔄 Milestone 7: Progress UI & Feedback
- [ ] Create progress bar in admin template
- [ ] Update progress bar after each batch
- [ ] Display real-time statistics (processed, skipped, errors)
- [ ] Show completion message
- [ ] Display detailed error messages if any
- [ ] Add loading state to button
- [ ] Disable form during processing
- [ ] Test progress updates work correctly

### 🔄 Milestone 8: Error Handling & Validation
- [ ] Validate attribute selection (not empty)
- [ ] Validate term selection (not empty)
- [ ] Check WooCommerce is active
- [ ] Handle WC_Product not found gracefully
- [ ] Log errors with product ID and details
- [ ] Return errors in AJAX response
- [ ] Display user-friendly error messages
- [ ] Test error scenarios

### 🔄 Milestone 9: Testing & Quality Assurance
- [ ] Run phpcs and fix violations
- [ ] Test with simple products
- [ ] Test with variable products
- [ ] Test with virtual/non-shippable products
- [ ] Test "Add" mode
- [ ] Test "Replace" mode
- [ ] Test with no products matching
- [ ] Test with large product sets
- [ ] Verify HPOS compatibility
- [ ] Use WP-CLI to verify attribute assignments
- [ ] Check translation strings
- [ ] Test nonce verification

### 🔄 Milestone 10: Documentation & Phase 1 Completion
- [ ] Add inline code documentation
- [ ] Create README.md with usage instructions
- [ ] Document known limitations
- [ ] Add screenshots if needed
- [ ] Commit to git with proper message
- [ ] Tag Phase 1 release
- [ ] Deploy to dev site for client testing

---

## Phase 2: Enhanced Features (Future)

### 🔲 Milestone 11: Product Query Builder UI
- [ ] Design query builder interface
- [ ] Add category multi-select
- [ ] Add tag multi-select  
- [ ] Add product type filter
- [ ] Add "All products" option
- [ ] Build WP_Query args from selections
- [ ] Show product count preview
- [ ] Test filters work correctly

### 🔲 Milestone 12: Bundle Product Support
- [ ] Research client's bundle plugin
- [ ] Add bundle product checkbox option
- [ ] Implement bundle product handling
- [ ] Test with bundle products

### 🔲 Milestone 13: Advanced Features
- [ ] Dry-run/preview mode
- [ ] Resume capability for interrupted operations
- [ ] Export results to CSV
- [ ] Detailed change logging
- [ ] Undo capability (if feasible)

---

## Active TODO Items

### High Priority
- [ ] Start Milestone 1: Create plugin foundation

### Medium Priority
- [ ] Decide on batch size (50 products recommended)
- [ ] Determine if bundle products need immediate support

### Low Priority
- [ ] Consider adding product count preview before processing
- [ ] Plan for multilingual support if needed

---

## Technical Decisions

### ✅ Confirmed Decisions
- **AJAX Batch Processing:** Yes, from Phase 1
- **Variation Handling:** Set on parent product only, use `set_variation(false)`
- **Shippable Check:** Use `$product->needs_shipping()`
- **Cache Management:** Use `wp_suspend_cache_addition()` during loops
- **Phase 1 Scope:** All products only, no query builder
- **Batch Size:** 50 products per batch (tentative)

### ❓ Pending Decisions
- Bundle product handling approach (depends on client's plugin)
- Dry-run mode priority
- Statistics detail level

---

## Technical Debt

*None yet - will track as project develops*

---

## Notes for Development

### WordPress Coding Standards
- No `declare(strict_types=1)` - WordPress/WooCommerce compatibility
- Use type hints and return types
- Follow phpcs WordPress ruleset
- Use `printf()` or `echo` for templates (no inline HTML)
- All button elements must include `button` CSS class

### WooCommerce HPOS
- Never use `get_post_meta()` for order/product data
- Always use WC_Product CRUD methods
- Declare HPOS compatibility in `before_woocommerce_init` hook

### Security Checklist
- [ ] Verify nonces on all AJAX requests
- [ ] Check `manage_woocommerce` capability
- [ ] Sanitize all input
- [ ] Escape all output
- [ ] Use prepared statements if custom queries

### Performance Considerations
- Suspend cache addition during intensive loops
- Process in batches (AJAX)
- Use `set_time_limit()` if needed
- Test with 1,000+ products before client deployment

### Testing with WP-CLI
```bash
# List product attributes (taxonomies)
wp taxonomy list --name=pa_*

# List terms for an attribute
wp term list pa_hscode

# Get product details
wp wc product get 123

# List all products
wp wc product list
```

---

## Resources

- **Requirements:** [dev-notes/requirements.md](requirements.md)
- **Coding Standards:** [.github/copilot-instructions.md](../.github/copilot-instructions.md)
- **Pattern Reference:** [dev-notes/patterns/](patterns/)
- **WooCommerce Docs:** https://woocommerce.github.io/code-reference/
- **WordPress Coding Standards:** https://developer.wordpress.org/coding-standards/

