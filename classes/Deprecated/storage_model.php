<?php
defined( 'ABSPATH' ) or die();

/**
 * Storage Model
 *
 * @since 2.0
 * @deprecated NEWVERSION
 */

/**
 * Storage Model
 *
 * @since 2.0
 * @deprecated NEWVERSION
 */
abstract class CPAC_Storage_Model extends AC_StorageModel {

	public function init() {
	}

	public function init_manage_value() {
	}

	public function add_headings( $columns ) {
		return $columns;
	}

}