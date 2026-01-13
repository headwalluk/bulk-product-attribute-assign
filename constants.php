<?php
/**
 * Plugin constants.
 *
 * @package Bulk_Product_Attribute_Assign
 */

namespace Bulk_Product_Attribute_Assign;

defined( 'ABSPATH' ) || die();

// AJAX actions.
const AJAX_ACTION_PROCESS_BATCH = 'bpaa_process_batch';
const AJAX_ACTION_GET_TERMS     = 'bpaa_get_terms';

// Nonce actions.
const NONCE_ACTION_PROCESS   = 'bpaa_process_attributes';
const NONCE_ACTION_GET_TERMS = 'bpaa_get_terms';

// Default values.
const DEF_BATCH_SIZE   = 50;
const DEF_MODE_ADD     = 'add';
const DEF_MODE_REPLACE = 'replace';

// Capability required.
const REQUIRED_CAPABILITY = 'manage_woocommerce';

// Menu slug.
const MENU_SLUG = 'bulk-product-attribute-assign';
