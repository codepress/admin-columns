<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_ID extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-link_id' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $id;
	}

}