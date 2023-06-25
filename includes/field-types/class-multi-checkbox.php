<?php
/**
 * Handles multiple checkboxes.
 *
 * @since 1.13.0
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Handles multiple checkboxes.
 *
 * @since 1.13.0
 */
class Noptin_Custom_Field_Multi_Checkbox extends Noptin_Custom_Field_Dropdown {
	protected $is_multiple = true;
}
