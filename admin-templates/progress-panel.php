<?php
/**
 * Progress and results panel template.
 *
 * Displays progress bar during batch processing and results summary.
 *
 * @package Bulk_Product_Attribute_Assign
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die();

// Progress section (hidden by default).
echo '<div id="bpaa-progress" style="display:none;">';
printf( '<h2>%s</h2>', esc_html__( 'Processing...', 'bulk-product-attribute-assign' ) );
echo '<div class="bpaa-progress-bar">';
echo '<div id="bpaa-progress-bar" class="bpaa-progress-fill" style="width: 0%;"></div>';
echo '</div>';
printf( '<p id="bpaa-progress-text">%s</p>', esc_html( '0%' ) );
echo '</div>';

// Results section (hidden by default).
echo '<div id="bpaa-results" style="display:none;">';
printf( '<h2>%s</h2>', esc_html__( 'Results', 'bulk-product-attribute-assign' ) );
echo '<div class="bpaa-results-content"></div>';
echo '</div>';
