<?php
/**
 * Product filter panel template.
 *
 * Provides advanced filtering options for targeting specific products.
 *
 * @package Bulk_Product_Attribute_Assign
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die();

// Toggle checkbox for showing/hiding filter panel.
printf(
	'<div class="bpaa-filter-toggle"><label><input type="checkbox" id="bpaa-show-filters" class="bpaa-toggle-filters"> %s</label></div>',
	esc_html__( 'Filter products', 'bulk-product-attribute-assign' )
);

// Filter panel container (hidden by default).
echo '<div id="bpaa-filter-panel" class="bpaa-filter-panel" style="display:none;">';

// Product Status filter.
echo '<div class="bpaa-filter-row">';
printf(
	'<label for="bpaa-product-status">%s</label>',
	esc_html__( 'Product Status', 'bulk-product-attribute-assign' )
);
echo '<select name="bpaa_product_status[]" id="bpaa-product-status" class="bpaa-selectwoo" multiple="multiple">';
printf( '<option value="publish" selected>%s</option>', esc_html__( 'Published', 'bulk-product-attribute-assign' ) );
printf( '<option value="draft">%s</option>', esc_html__( 'Draft', 'bulk-product-attribute-assign' ) );
printf( '<option value="pending">%s</option>', esc_html__( 'Pending', 'bulk-product-attribute-assign' ) );
printf( '<option value="private">%s</option>', esc_html__( 'Private', 'bulk-product-attribute-assign' ) );
echo '</select>';
echo '</div>';

// Product Categories filter.
echo '<div class="bpaa-filter-row">';
printf(
	'<label for="bpaa-categories">%s</label>',
	esc_html__( 'Product Categories', 'bulk-product-attribute-assign' )
);
echo '<select name="bpaa_categories[]" id="bpaa-categories" class="bpaa-selectwoo" multiple="multiple">';
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Local variables in template.
$bpaa_categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	)
);
if ( ! is_wp_error( $bpaa_categories ) && ! empty( $bpaa_categories ) ) {
	foreach ( $bpaa_categories as $bpaa_category ) {
		printf(
			'<option value="%d">%s</option>',
			esc_attr( $bpaa_category->term_id ),
			esc_html( $bpaa_category->name )
		);
	}
}
// phpcs:enable
echo '</select>';
echo '</div>';

// Product Tags filter.
echo '<div class="bpaa-filter-row">';
printf(
	'<label for="bpaa-tags">%s</label>',
	esc_html__( 'Product Tags', 'bulk-product-attribute-assign' )
);
echo '<select name="bpaa_tags[]" id="bpaa-tags" class="bpaa-selectwoo" multiple="multiple">';
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Local variables in template.
$bpaa_tags = get_terms(
	array(
		'taxonomy'   => 'product_tag',
		'hide_empty' => false,
	)
);
if ( ! is_wp_error( $bpaa_tags ) && ! empty( $bpaa_tags ) ) {
	foreach ( $bpaa_tags as $bpaa_tag ) {
		printf(
			'<option value="%d">%s</option>',
			esc_attr( $bpaa_tag->term_id ),
			esc_html( $bpaa_tag->name )
		);
	}
}
// phpcs:enable
echo '</select>';
echo '</div>';

// Product Name search.
echo '<div class="bpaa-filter-row">';
printf(
	'<label for="bpaa-name-search">%s</label>',
	esc_html__( 'Product Name Contains', 'bulk-product-attribute-assign' )
);
printf(
	'<input type="text" name="bpaa_name_search" id="bpaa-name-search" class="regular-text" placeholder="%s">',
	esc_attr__( 'Search by name...', 'bulk-product-attribute-assign' )
);
echo '</div>';

// Virtual products checkbox.
echo '<div class="bpaa-filter-row">';
printf(
	'<label><input type="checkbox" name="bpaa_include_virtual" value="1"> %s</label>',
	esc_html__( 'Include virtual products', 'bulk-product-attribute-assign' )
);
echo '</div>';

// Downloadable products checkbox.
echo '<div class="bpaa-filter-row">';
printf(
	'<label><input type="checkbox" name="bpaa_include_downloadable" value="1"> %s</label>',
	esc_html__( 'Include downloadable products', 'bulk-product-attribute-assign' )
);
echo '</div>';

echo '</div>'; // Close filter panel.
