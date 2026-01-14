<?php
/**
 * Admin page template for bulk assigning product attributes.
 *
 * @package Bulk_Product_Attribute_Assign
 */

defined( 'ABSPATH' ) || die();

// Page wrapper.
echo '<div class="wrap">';
printf( '<h1>%s</h1>', esc_html( get_admin_page_title() ) );

echo '<div class="bpaa-admin-wrapper">';

// Form start.
echo '<form id="bpaa-form" method="post">';
wp_nonce_field( \Bulk_Product_Attribute_Assign\NONCE_ACTION_PROCESS, 'bpaa_nonce' );

// Form table.
echo '<table class="form-table" role="presentation"><tbody>';

// Product Attribute row.
echo '<tr>';
printf(
	'<th scope="row"><label for="bpaa-attribute">%s</label></th>',
	esc_html__( 'Product Attribute', 'bulk-product-attribute-assign' )
);
echo '<td>';
echo '<select id="bpaa-attribute" name="attribute" class="regular-text" required>';
printf( '<option value="">%s</option>', esc_html__( 'Select an attribute...', 'bulk-product-attribute-assign' ) );

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Local variables in template.
$bpaa_attributes = wc_get_attribute_taxonomies();
foreach ( $bpaa_attributes as $bpaa_attribute ) {
	printf(
		'<option value="%s">%s</option>',
		esc_attr( $bpaa_attribute->attribute_name ),
		esc_html( $bpaa_attribute->attribute_label )
	);
}
// phpcs:enable

echo '</select>';
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Select the product attribute you want to update.', 'bulk-product-attribute-assign' )
);
echo '</td></tr>';

// Attribute Terms row.
echo '<tr>';
printf(
	'<th scope="row"><label for="bpaa-terms">%s</label></th>',
	esc_html__( 'Attribute Terms', 'bulk-product-attribute-assign' )
);
echo '<td>';
echo '<select id="bpaa-terms" name="terms[]" class="regular-text" multiple="multiple" required disabled>';
printf( '<option value="">%s</option>', esc_html__( 'Select an attribute first...', 'bulk-product-attribute-assign' ) );
echo '</select>';
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Select one or more terms to assign to products.', 'bulk-product-attribute-assign' )
);
echo '</td></tr>';

// Mode row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Mode', 'bulk-product-attribute-assign' ) );
echo '<td><fieldset>';
printf(
	'<label><input type="radio" name="mode" value="%s" checked> %s</label><br>',
	esc_attr( \Bulk_Product_Attribute_Assign\DEF_MODE_ADD ),
	esc_html__( 'Add to existing terms', 'bulk-product-attribute-assign' )
);
printf(
	'<label><input type="radio" name="mode" value="%s"> %s</label>',
	esc_attr( \Bulk_Product_Attribute_Assign\DEF_MODE_REPLACE ),
	esc_html__( 'Replace all existing terms', 'bulk-product-attribute-assign' )
);
printf(
	'<p class="description">%s</p>',
	esc_html__( 'Choose whether to add terms or replace existing ones.', 'bulk-product-attribute-assign' )
);
echo '</fieldset></td></tr>';

// Options row.
echo '<tr>';
printf( '<th scope="row">%s</th>', esc_html__( 'Options', 'bulk-product-attribute-assign' ) );
echo '<td><fieldset>';
printf(
	'<label><input type="checkbox" name="attribute_visible" value="1"> %s</label><br>',
	esc_html__( 'Attribute visible on product page', 'bulk-product-attribute-assign' )
);
printf(
	'<label><input type="checkbox" name="preview_only" value="1"> %s</label>',
	esc_html__( 'Preview only (dry-run - don\'t make changes)', 'bulk-product-attribute-assign' )
);
echo '</fieldset></td></tr>';

echo '</tbody></table>';

// Product count display.
echo '<div class="bpaa-product-count">';
printf(
	'<p><strong>%s</strong> <span id="bpaa-count">-</span></p>',
	esc_html__( 'Products to process:', 'bulk-product-attribute-assign' )
);
echo '</div>';

// Submit buttons.
echo '<p class="submit">';
printf(
	'<button type="submit" class="button button-primary button-large" id="bpaa-submit">%s</button>',
	esc_html__( 'Set attributes now...', 'bulk-product-attribute-assign' )
);
printf(
	'<button type="button" class="button button-secondary" id="bpaa-reset">%s</button>',
	esc_html__( 'Reset options', 'bulk-product-attribute-assign' )
);
echo '</p>';

// Filter panel.
require_once __DIR__ . '/filter-panel.php';

// Close form.
echo '</form>';

// Progress and results panels.
require_once __DIR__ . '/progress-panel.php';

// Close wrappers.
echo '</div>'; // .bpaa-admin-wrapper
echo '</div>'; // .wrap
