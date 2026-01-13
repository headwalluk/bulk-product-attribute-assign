# Bulk Assign Product Attributes - Requirements

**Plugin Name:** Bulk Assign Product Attributes  
**Plugin Slug:** `bulk-product-attribute-assign`  
**Version:** 1.0.0  
**Created:** 13 January 2026  
**Last Updated:** 13 January 2026

---

## Background

### Business Context

The client operates a busy WooCommerce store with:
- Hundreds of products (1,000+ including variations)
- New requirement for overseas orders leaving the United Kingdom
- Need to add customs/import metadata for receivers on order line items

### Required Product Attributes

Three new product attributes have been created for customs compliance:

1. **Country of Origin** (e.g., "GB")
2. **HS Code** (Harmonized System/Commodity Code, e.g., "8211920000")
3. **Customs Description** (e.g., "kitchen knife")

### Current Situation

- Attributes and generic terms already created in WooCommerce
- Client needs to set all products to default values initially
- Client will manually refine products over time
- Bulk operation needed to set baseline attributes across entire catalog

---

## Core Requirements

### Functional Requirements

#### 1. Attribute Selection
- User selects a product attribute from dropdown (e.g., `hscode`)
- User selects one or more attribute terms for that attribute
- Terms should be loaded dynamically based on selected attribute

#### 2. Operation Mode
Two modes for applying terms:
- **Add mode** (default): Add new terms to existing terms
- **Replace mode**: Replace all existing terms with new terms

#### 3. Product Filtering
User builds a query to target specific products:
- Filter by product category
- Filter by product tag
- Select all products
- **Note:** Keep simple initially, extend later

#### 4. Execution Flow
1. User clicks "Set attributes now..."
2. JavaScript confirmation dialog warns:
   - Operation cannot be undone
   - Requires explicit confirmation
3. Backend processes products using `wc_get_products()`

#### 5. Product Processing Logic

**For each product:**
- Skip if product is not shippable
- Handle based on product type:

**Variable Products:**
- Set attribute on the parent product (NOT on variations)
- If attribute needs to be added:
  - Create new `WC_Product_Attribute` instance
  - Set `$attribute->set_variation(false)` to prevent use in variation differentiation
  - Add to product's attributes array
- If attribute already exists on parent:
  - Use existing attribute as-is
  - Add or replace terms based on selected mode

**Simple Products:**
- Add attribute if not already present
- Add or replace terms based on selected mode

**Bundle Products:**
- Default: Skip bundle products
- Optional: Checkbox to include bundle products in operation

#### 6. Results Reporting
Display comprehensive statistics:
- Number of products skipped
- Number of products updated
- Number of products that didn't need updates
- Number of failed operations
- List of any errors encountered

#### 7. Admin Interface
- Location: WooCommerce → Tools menu
- Clean, intuitive interface
- Clear labeling and instructions
- Visual feedback during processing

---

## Technical Considerations

### WooCommerce HPOS Compatibility
- Must use WC_Product CRUD methods (never `get_post_meta()`)
- Declare HPOS compatibility
- Test with both traditional and HPOS data stores

### Product Attributes in WooCommerce

**Key Concepts:**
- Product attributes can be global (registered taxonomy) or local (product-specific)
- Terms belong to registered taxonomies (e.g., `pa_hscode`)
- Variations use attribute values, not terms
- Attributes on variations vs. parent products behave differently

**Questions to Resolve:**
1. Are the client's attributes registered globally (as taxonomies)?
2. For variable products:
   - Should attributes be set on the parent product?
   - Should attributes be set on individual variations?
   - The requirement states "NOT used for variations" - clarify meaning
3. How do we handle products that already have these attributes set?

### Performance Considerations

- Object cache saturation from intensive queries

**Mitigation Strategies:**
- Use batch processing with AJAX ✓ (confirmed for Phase 1)
- Process in chunks (e.g., 50 products at a time)
- Display real-time progress bar
- Implement resume capability for interrupted operations
- Use `set_time_limit()` and increase memory if needed
- **Suspend cache addition during intensive loops:**
  ```php
  wp_suspend_cache_addition(true);
  // Process products...
  wp_suspend_cache_addition(false);
  ```
- Process in chunks (e.g., 50 products at a time)
- Display real-time progress bar
- Implement resume capability for interrupted operations
- Use `set_time_limit()` and increase memory if needed

### Data Integrity

**Concerns:**
- Operation is irreversible (no built-in undo)
- Potential for data loss if misconfigured
- Need robust validation before execution

**Safeguards:**
- Confirmation dialog with clear warning
- Dry-run option (preview changes without applying)
- Detailed logging of all changes
- Backup recommendation in UI

### Product Type Handling

**Supported Types:**
- Simple products ✓
- Variable products (and variations) ✓
- Bundle products (optional) ?

**Edge Cases:**
- Grouped products
- External/Affiliate products
- Virtual/downloadable products
- What defines "shippable"? Use `$product->needs_shipping()`?

---

## Potential Problems & Questions

### 1. Attribute Architecture
**Question:** Are custom attributes registered as global taxonomies (`pa_*`) or local attributes?
- **Impact:** Different handling for global vs. local attributes
- **API Differences:**
  - Global: Use `wp_set_object_terms()`
  - Local: Use `$product->set_attributes()` and save

### 2. Variable Products & Variations
**Question:** The requirement says attributes "should NOT be used for variations" - what does this mean?
- Does it mean: Don't set the "used_for_variations" flag?
- Or: Don't apply attributes to variation products at all?
- **Current understanding:** Set attributes on variations, but don't use them for variation selection

### 3. Bundle Products
**Question:** What product bundle plugin is in use?
- WooCommerce Product Bundles (official)
- YITH WooCommerce Product Bundles
- Other?
- **Impact:** Different handling based on implementation

### 4. Shippable Products
**Question:** How do we determine if a product is shippable?
- Use `$product->needs_shipping()`?
- Check virtual/downloadable flags?
- Custom logic?

### 5. Batch Processing
**Decision Needed:** Should we implement batch processing from the start or add if needed?
- **Pros:** Prevents timeouts, better UX, resume capability
- **Cons:** More complex implementation, more code to test

### 6. Dry Run Mode
**Question:** Should we add a "Preview Changes" mode?
- Shows what would change without making changes
- Helps user verify before committing
- Adds development time but improves safety

---

## Proposed Architecture

### File Structure
```
bulk-product-attribute-assign/
├── bulk-product-attribute-assign.php    # Main plugin file
├── constants.php                         # Plugin constants
├── includes/
│   ├── class-plugin.php                 # Main plugin class
│   ├── class-admin-hooks.php            # Admin hooks & menu
│   ├── class-attribute-processor.php    # Core processing logic
│   └── class-ajax-handler.php           # AJAX endpoints
├── admin-templates/
│   └── bulk-assign-page.php             # Admin UI template
├── assets/
│   └── admin/
│       ├── bulk-assign.css              # Admin styles
│       └── bulk-assign.js               # Admin JavaScript
└── languages/
    └── bulk-product-attribute-assign.pot
```

### Key Classes

**Plugin**
- Initialize plugin
- Register hooks
- Lazy-load dependencies

**Admin_Hooks**
- Register admin menu under WooCommerce → Tools
- Enqueue admin assets
- Render admin page

**Attribute_Processor**
- Validate input parameters
- Query products based on filters
- Process products and variations
- Handle attribute assignment logic
- Track statistics and errors

**Ajax_Handler**
- Handle AJAX requests
- Coordinate batch processing
- Return progress updates and results

### Processing Flow

1. **User Input** → Validate selections
2. **Confirmation** → JavaScript alert with warning
3. **AJAX Request** → Send to backend
4. **Query Products** → Build WP_Query based on filters
5. **Process Batch** → Loop through products
   - Check if shippable
   - Handle by product type
   - Apply attributes/terms
   - Track results
6. **Return Results** → Send statistics and errors
7. **Display Feedback** → Show results to user

### AJAX Batch Processing (Recommended)

```
Client                          Server
  |                               |
  |--- Batch 1 (products 0-49) -->|
  |<-- Progress: 50/1000 ---------|
  |                               |
  |--- Batch 2 (products 50-99) ->|
  |<-- Progress: 100/1000 --------|
  |                               |
  ... continue until complete ...
  |                               |
  |<-- Final results -------------|
```
Both modes: "Add to existing" (default) and "Replace"
- Filter: All products only (no query builder UI)
- **AJAX batch processing with progress bar** ✓
- Real-time statistics updates
- Suspend cache addition during processing
- Comprehensive results display

### Phase 2: Enhanced Features
- Product category/tag filters (query builder UI)
- Bundle product handling option
- Improved error handling and validation
- Dry-run/preview mode (optional)

### Phase 3: Advanced Features
- Resume capability for interrupted operations
- Detailed change logging
- Export results to CSV
- Undo capability (if feasible)ndling option
- Improved error handling and validation

### Phase 3: Performance & UX
- Implement batch processing with AJAX
- Add progress bar
- Real-time statistics updates
- Resume capability

### Phase 4: Safety & Convenience
- Dry-run/preview mode
- Detailed change logging
- Export results to CSV
- Undo capability (if feasible)

---

## Decisions & Clarifications

### ✅ Resolved Questions

1. **Product Attributes:** Global taxonomies (`pa_*`), but plugin should handle any product attribute
   
2. **Variation Handling:** 
   - DO NOT loop over variations
   - Set attributes on the Variable Product (parent) only
   - When adding new attributes: use `$attribute->set_variation(false)` from `WC_Product_Attribute` class
   - If attribute already exists on parent, use as-is

3. **Shippable Logic:** Use `$product->needs_shipping()` to determine if product should be processed

4. **Batch Processing:** Implement AJAX batch processing from the start with progress UI

5. **Phase 1 Scope:** "All products" only - no query builder UI yet (save for Phase 2)

### ⚠️ Open Questions
Development Environment

### Available Tools
- **WP-CLI:** Available for testing and inspection
  ```bash
  wp taxonomy list
  wp post-type list
  wp term list pa_attribute_name
  ```

### Testing Approach
- Dev site has small product selection (good for Phase 1)
- Real client site has 1,000+ products
- Test thoroughly on dev before client deployment

---

## Next Steps

1. ✅ **Discussion:** Requirements clarified
2. ✅ **Refinement:** Phase 1 scope defined
3. **Implementation:** Begin Phase 1 development
   - Create plugin structure
   - Build admin UI
   - Implement AJAX batch processing
   - Add progress tracking
4. **Testing:** Test on dev site with WP-CLI verification
5. **Deployment:** Move to client site once validated

## Next Steps

1. **Discussion:** Review and clarify open questions
2. **Refinement:** Adjust requirements based on feedback
3. **Planning:** Finalize architecture and development phases
4. **Implementation:** Begin Phase 1 development
5. **Testing:** Test with client's product catalog
6. **Iteration:** Gather feedback and enhance

---

## Notes

- Follow WordPress Coding Standards (verify with phpcs)
- Use PHP 8.0+ type hints
- HPOS compatibility is critical
- All operations must be translatable
- Security: Verify nonces, check capabilities, sanitize input
- No `declare(strict_types=1)` per WordPress compatibility requirements
