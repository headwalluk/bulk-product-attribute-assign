<?php
/**
 * AJAX request handler.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

/**
 * Ajax Handler class.
 *
 * Handles AJAX requests for batch processing and term retrieval.
 *
 * @since 1.0.0
 */
class Ajax_Handler {
	/**
	 * Attribute processor instance.
	 *
	 * @var Attribute_Processor
	 */
	private Attribute_Processor $attribute_processor;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Attribute_Processor $attribute_processor Attribute processor instance.
	 */
	public function __construct( Attribute_Processor $attribute_processor ) {
		$this->attribute_processor = $attribute_processor;
	}

	/**
	 * Handle batch processing AJAX request.
	 *
	 * @since 1.0.0
	 */
	public function process_batch(): void {
		// TODO: Implement batch processing AJAX handler.
	}

	/**
	 * Handle get terms AJAX request.
	 *
	 * @since 1.0.0
	 */
	public function get_terms(): void {
		// TODO: Implement get terms AJAX handler.
	}
}
