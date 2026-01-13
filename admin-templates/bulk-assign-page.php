<?php
/**
 * Admin page template for bulk assigning product attributes.
 *
 * @package Bulk_Product_Attribute_Assign
 */

defined( 'ABSPATH' ) || die();

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="bpaa-admin-wrapper">
		<form id="bpaa-form" method="post">
			<?php wp_nonce_field( \Bulk_Product_Attribute_Assign\NONCE_ACTION_PROCESS, 'bpaa_nonce' ); ?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="bpaa-attribute">
								<?php esc_html_e( 'Product Attribute', 'bulk-product-attribute-assign' ); ?>
							</label>
						</th>
						<td>
							<select id="bpaa-attribute" name="attribute" class="regular-text" required>
								<option value=""><?php esc_html_e( 'Select an attribute...', 'bulk-product-attribute-assign' ); ?></option>
								<?php
								// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Local variables in template.
								// Get all product attributes (global taxonomies).
								$attributes = wc_get_attribute_taxonomies();
								foreach ( $attributes as $attribute ) {
									printf(
										'<option value="%s">%s</option>',
										esc_attr( $attribute->attribute_name ),
										esc_html( $attribute->attribute_label )
									);
								}
								// phpcs:enable
								?>
							</select>
							<p class="description">
								<?php esc_html_e( 'Select the product attribute you want to update.', 'bulk-product-attribute-assign' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="bpaa-terms">
								<?php esc_html_e( 'Attribute Terms', 'bulk-product-attribute-assign' ); ?>
							</label>
						</th>
						<td>
							<select id="bpaa-terms" name="terms[]" class="regular-text" multiple="multiple" required disabled>
								<option value=""><?php esc_html_e( 'Select an attribute first...', 'bulk-product-attribute-assign' ); ?></option>
							</select>
							<p class="description">
								<?php esc_html_e( 'Select one or more terms to assign to products.', 'bulk-product-attribute-assign' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<?php esc_html_e( 'Mode', 'bulk-product-attribute-assign' ); ?>
						</th>
						<td>
							<fieldset>
								<label>
									<input type="radio" name="mode" value="<?php echo esc_attr( \Bulk_Product_Attribute_Assign\DEF_MODE_ADD ); ?>" checked>
									<?php esc_html_e( 'Add to existing terms', 'bulk-product-attribute-assign' ); ?>
								</label>
								<br>
								<label>
									<input type="radio" name="mode" value="<?php echo esc_attr( \Bulk_Product_Attribute_Assign\DEF_MODE_REPLACE ); ?>">
									<?php esc_html_e( 'Replace all existing terms', 'bulk-product-attribute-assign' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Choose whether to add terms or replace existing ones.', 'bulk-product-attribute-assign' ); ?>
								</p>
							</fieldset>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<?php esc_html_e( 'Options', 'bulk-product-attribute-assign' ); ?>
						</th>
						<td>
							<fieldset>
								<label>
									<input type="checkbox" name="include_virtual" value="1">
									<?php esc_html_e( 'Include virtual/downloadable products', 'bulk-product-attribute-assign' ); ?>
								</label>
								<br>
								<label>
									<input type="checkbox" name="preview_only" value="1">
									<?php esc_html_e( 'Preview only (dry-run - don\'t make changes)', 'bulk-product-attribute-assign' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'By default, only shippable products are processed.', 'bulk-product-attribute-assign' ); ?>
								</p>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class="bpaa-product-count">
				<p>
					<strong><?php esc_html_e( 'Products to process:', 'bulk-product-attribute-assign' ); ?></strong>
					<span id="bpaa-count">-</span>
				</p>
			</div>
			
			<p class="submit">
				<button type="submit" class="button button-primary button-large" id="bpaa-submit">
					<?php esc_html_e( 'Set attributes now...', 'bulk-product-attribute-assign' ); ?>
				</button>
			</p>
		</form>
		
		<div id="bpaa-progress" style="display:none;">
			<h2><?php esc_html_e( 'Processing...', 'bulk-product-attribute-assign' ); ?></h2>
			<div class="bpaa-progress-bar">
				<div id="bpaa-progress-bar" class="bpaa-progress-fill" style="width: 0%;"></div>
			</div>
			<p id="bpaa-progress-text">0%</p>
		</div>
		
		<div id="bpaa-results" style="display:none;">
			<h2><?php esc_html_e( 'Results', 'bulk-product-attribute-assign' ); ?></h2>
			<div class="bpaa-results-content"></div>
		</div>
	</div>
</div>
