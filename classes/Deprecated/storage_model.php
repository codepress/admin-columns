<?php
defined( 'ABSPATH' ) or die();

/**
 * Storage Model
 *
 * @since 2.0
 * @deprecated NEWVERSION
 */
abstract class CPAC_Storage_Model {

	public function __construct() {
	}

	public function add_headings( $columns ) {
		return $columns;
	}

	public function get_post_type() {
	}

	public function is_current_screen() {
	}

	public function get_menu_type() {
	}

}