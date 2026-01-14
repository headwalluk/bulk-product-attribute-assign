# Bulk Assign Product Attributes - Project Tracker

**Version:** 1.2.0  
**Last Updated:** 14 January 2026

---

## Overview

WordPress/WooCommerce plugin to bulk-assign product attribute terms to products. Created to help client set customs metadata (Country of Origin, HS Code, Customs Description) across 1,000+ products for international shipping compliance.

**Plugin Slug:** `bulk-product-attribute-assign`  
**Plugin Name:** Bulk Assign Product Attributes  
**Admin Location:** WooCommerce → Bulk Assign Attributes

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

### ✅ Milestone 2: Admin Integration
- [x] Create includes/class-admin-hooks.php
- [x] Register admin menu under WooCommerce → Tools
- [x] Create admin-templates/bulk-assign-page.php
- [x] Implement lazy loading for admin hooks
- [x] Enqueue admin assets (CSS/JS) conditionally
- [x] Add capability checks (manage_woocommerce)
- [x] Test menu appears in correct location

### ✅ Milestone 3: Admin UI - Basic Form
- [x] Create attribute selection dropdown (using SelectWoo)
- [x] Load product attributes dynamically (global taxonomies)
- [x] Create term selection using SelectWoo (multi-select)
- [x] Add mode selector (Add/Replace radio buttons)
- [x] Add "Include virtual/downloadable products" checkbox
- [x] Add "Preview only (dry-run)" checkbox
- [x] Add "Set attributes now" button
- [x] Add nonce field for security
- [x] Add product count preview ("This will affect X products")
- [x] Style with WordPress admin styles
- [x] Test form renders correctly

### ✅ Milestone 4: JavaScript & AJAX Setup
- [x] Create assets/admin/bulk-assign.js
- [x] Create assets/admin/bulk-assign.css
- [x] Implement confirmation dialog on submit
- [x] Set up AJAX request to backend
- [x] Create includes/class-ajax-handler.php
- [x] Register AJAX actions (admin only)
- [x] Pass nonce and AJAX URL to JavaScript
- [x] Test AJAX communication works

### ✅ Milestone 5: Core Processing Logic
- [x] Create includes/class-attribute-processor.php
- [x] Implement get_products_to_process() method
- [x] Implement get_product_count() method for preview
- [x] Implement process_single_product() method
- [x] Handle simple products correctly
- [x] Handle variable products (parent only, variations inherit)
- [x] Respect "Include virtual/downloadable" option
- [x] Implement WooCommerce attribute structure (WC_Product_Attribute)
- [x] Implement "Add" mode (add terms to existing)
- [x] Implement "Replace" mode (replace all terms)
- [x] Implement "Preview" mode (dry-run - no changes)
- [x] Better error context (include product ID, type, reason)
- [x] Test with various product types

### ✅ Milestone 6: Batch Processing
- [x] Implement batch processing in AJAX handler
- [x] Process products in dynamic chunks (ceil(total/3), capped at 50)
- [x] Track progress across batches
- [x] Return batch results to frontend
- [x] Implement wp_suspend_cache_addition() wrapper
- [x] Handle timeouts gracefully
- [x] Test with larger product sets

### ✅ Milestone 7: Progress UI & Feedback
- [x] Create progress bar in admin template
- [x] Update progress bar after each batch
- [x] Display real-time statistics (processed, skipped, errors)
- [x] Show completion message
- [x] Display detailed error messages with context (product ID, reason)
- [x] Add loading state to button
- [x] Disable form during processing
- [x] Handle dry-run results display
- [x] Test progress updates work correctly
- [x] Fix progress bar reset between runs

### ✅ Milestone 8: Error Handling & Validation
- [x] Validate attribute selection (not empty)
- [x] Validate term selection (not empty)
- [x] Check WooCommerce is active
- [x] Handle WC_Product not found gracefully
- [x] Log errors with product ID and details
- [x] Return errors in AJAX response
- [x] Display user-friendly error messages
- [x] Test error scenarios

### ✅ Milestone 9: Testing & Quality Assurance
- [x] Run phpcs and fix violations
- [x] Test with simple products
- [x] Test with variable products
- [x] Test with virtual/downloadable products
- [x] Test "Include virtual/downloadable" option
- [x] Test "Add" mode
- [x] Test "Replace" mode
- [x] Test "Preview" mode (dry-run)
- [x] Test product count preview accuracy
- [x] Test with no products matching
- [x] Test with multiple products (32 tested successfully)
- [x] Verify HPOS compatibility
- [x] Use WP-CLI to verify attribute assignments
- [x] Check translation strings
- [x] Test nonce verification
- [x] Verify detailed error messages appear correctly

### ✅ Milestone 10: Security Audit & Phase 1 Completion
- [x] Add inline code documentation
- [x] Security audit: nonce verification on all AJAX endpoints
- [x] Security audit: capability checks on all AJAX endpoints and admin page
- [x] Security audit: input sanitization review
- [x] Security audit: output escaping review
- [x] Add filterable capability requirement (bpaa_required_capability)
- [x] Create README.md with usage instructions
- [x] Document known limitations
- [x] Final phpcs check and fixes
- [x] Commit Phase 1 to git with proper message
- [x] Tag v1.0.0 release
- [x] Deploy to dev site for client testing

### ✅ Milestone 11: v1.1.0 - Attribute Visibility Control
- [x] Add "Attribute visible on product page" checkbox to Options section
- [x] Update admin template with new checkbox (first option)
- [x] Update JavaScript to capture and pass visibility parameter
- [x] Update AJAX handler to accept attribute_visible parameter
- [x] Update Attribute_Processor to use visibility parameter in set_visible()
- [x] Set default to false (unchecked/hidden)
- [x] Test with products to verify frontend visibility control
- [x] Run phpcs and verify code standards compliance
- [x] Update CHANGELOG.md for v1.1.0
- [x] Update README.md stable tag
- [x] Update readme.txt with v1.1.0 changelog
- [x] Commit v1.1.0 changes to git
- [x] Tag v1.1.0 release

---

## Security Audit Summary (v1.0.0)

**Status:** ✅ PASSED - Ready for v1.0.0 release

### Nonce Verification
- ✅ `process_batch()` - Uses `NONCE_ACTION_PROCESS`
- ✅ `get_terms()` - Uses `NONCE_ACTION_GET_TERMS`
- ✅ `get_product_count()` - Uses `NONCE_ACTION_GET_PRODUCT_COUNT`
- ✅ `get_total_count()` - Uses `NONCE_ACTION_GET_TOTAL_COUNT`
- ✅ Admin form - Uses `wp_nonce_field()` with `NONCE_ACTION_PROCESS`

### Capability Checks
- ✅ All AJAX handlers check `current_user_can( $required_capability )`
- ✅ Admin menu registration uses capability requirement
- ✅ `render_admin_page()` checks capability before rendering
- ✅ Capability is filterable via `bpaa_required_capability` filter
- ✅ Default capability: `manage_woocommerce`

### Input Sanitization
- ✅ `attribute` - `sanitize_text_field()`
- ✅ `term_ids` - `array_map( 'absint', ... )`
- ✅ `mode` - `sanitize_text_field()` + validation against allowed values
- ✅ `include_virtual` - `filter_var( ..., FILTER_VALIDATE_BOOLEAN )`
- ✅ `dry_run` - `filter_var( ..., FILTER_VALIDATE_BOOLEAN )`
- ✅ `offset` - `absint()`
- ✅ `batch_size` - `absint()`
- ✅ All `$_POST` data uses `wp_unslash()`

### Output Escaping
- ✅ Admin template uses `esc_html()`, `esc_attr()`, `esc_html_e()`, `esc_html__()`
- ✅ No raw output of user-supplied data
- ✅ JavaScript localization uses `wp_localize_script()` (auto-escaped)
- ✅ AJAX responses use structured arrays (JSON-encoded by WordPress)

### Data Validation
- ✅ Empty attribute check
- ✅ Empty term IDs check
- ✅ Mode validation against whitelist (`DEF_MODE_ADD`, `DEF_MODE_REPLACE`)
- ✅ Taxonomy existence check
- ✅ Term ID validation against taxonomy
- ✅ Product existence check

### WordPress Best Practices
- ✅ No direct SQL queries (uses WooCommerce CRUD and `wp_set_object_terms()`)
- ✅ HPOS compatible (uses WC_Product methods, not `get_post_meta()`)
- ✅ Proper phpcs compliance (WordPress Coding Standards)
- ✅ Translation-ready strings
- ✅ Cache suspension for performance
- ✅ Error handling with `WP_Error`

---

## Phase 2: Enhanced Features (Future)

### ✅ Milestone 11: Product Query Builder UI (v1.2.0)
- [x] Design query builder interface (progressive disclosure filter panel)
- [x] Add product status multi-select filter
- [x] Add category multi-select
- [x] Add tag multi-select
- [x] Add name search filter
- [x] Build wc_get_products() args from filter selections
- [x] Show product count preview with filters
- [x] Add filter enable/disable toggle
- [x] Add reset options button
- [x] Test filters work correctly with WooCommerce API
- [x] Create modular filter-panel.php template
- [x] Refactor all templates to code-first pattern

### 🔲 Milestone 12: Bundle Product Support
- [ ] Research client's bundle plugin
- [ ] Add bundle product checkbox option
- [ ] Implement bundle product handling
- [ ] Test with bundle products

---

## Phase 3: Nice-to-Have Features (Future)

### 🔲 Milestone 13: Resume Capability
- [ ] Store progress in transient during processing
- [ ] Detect interrupted operations
- [ ] Add "Resume last operation" button
- [ ] Test resume functionality with interrupted operations

---

## Active TODO Items

### High Priority
- [ ] Commit Phase 1 to git with proper message
- [ ] Tag v1.0.0 release

### Completed
- [x] Plugin foundation (Milestone 1)
- [x] Admin integration (Milestone 2)
- [x] Admin UI form (Milestone 3)
- [x] JavaScript & AJAX setup (Milestone 4)
- [x] Core processing logic (Milestone 5)
- [x] Batch processing (Milestone 6)
- [x] Progress UI & feedback (Milestone 7)
- [x] Error handling & validation (Milestone 8)
- [x] Testing & quality assurance (Milestone 9)
- [x] Security audit & documentation (Milestone 10)

---

## Technical Decisions

### ✅ Confirmed Decisions
- **AJAX Batch Processing:** Yes, implemented in Phase 1
- **Variation Handling:** Set on parent product only, variations inherit attributes
- **Shippable Check:** Use `$product->needs_shipping()` (virtual/downloadable filter)
- **Include Virtual/Downloadable:** Optional checkbox in Phase 1 - implemented
- **Cache Management:** `wp_suspend_cache_addition()` during loops - implemented
- **Phase 1 Scope:** All products only, no query builder - completed
- **Batch Size:** Dynamic sizing: `ceil(total/3)`, capped at 50, filterable via `bpaa_batch_size`
- **Select2 Usage:** WooCommerce's bundled SelectWoo for attribute/term selection
- **Product Count Preview:** Shows "This will affect X products" - implemented
- **Dry-Run Mode:** Preview mode included in Phase 1 - implemented
- **Error Details:** Include product ID, type, and reason in error messages - implemented
- **Capability Requirement:** `manage_woocommerce` by default, filterable via `bpaa_required_capability`
- **Security:** All AJAX endpoints have nonce verification and capability checks

### ❌ Not Needed
- CSV export of results
- Attribute term validation (Select2 handles this)
- Undo capability (confirmed not feasible)

### ❓ Pending Decisions
- Bundle product handling approach (depends on client's plugin)
- Dry-run mode priority
- Statistics detail level

---

## Technical Debt

### Fixed Issues
- ✅ SelectWoo styling (switched from Select2 to WooCommerce's SelectWoo)
- ✅ JavaScript corruption (syntax errors fixed)
- ✅ Product count returning 0 (removed unnecessary attribute check)
- ✅ WC_Product_Attribute conversion error (rebuilt attributes array properly)
- ✅ Variations causing errors (only process parent products)
- ✅ Progress bar element IDs missing (added IDs to template)
- ✅ Progress bar showing 100% from previous run (reset in showProgress)
- ✅ Commented-out code in class-admin-hooks.php (cleaned up)
- ✅ phpcs alignment violations (auto-fixed with phpcbf)

### Current Status
- **No technical debt** - All known issues resolved
- **Code quality:** 100% phpcs compliant
- **Security:** All endpoints protected with nonces and capability checks

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
- [x] Verify nonces on all AJAX requests
- [x] Check `manage_woocommerce` capability (filterable)
- [x] Sanitize all input
- [x] Escape all output
- [x] Use WooCommerce CRUD methods (no direct SQL queries)
- [x] Validate all user inputs against expected values

### Performance Considerations
- [x] Suspend cache addition during intensive loops
- [x] Process in batches (AJAX)
- [x] Use dynamic batch sizing for visual feedback
- [x] Test with 1,000+ products before client deployment (tested with 32 successfully)

### Filter Hooks Available
- `bpaa_batch_size` - Customize batch size (receives `$batch_size`, `$total_count`)
- `bpaa_required_capability` - Customize required user capability (default: `manage_woocommerce`)

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

