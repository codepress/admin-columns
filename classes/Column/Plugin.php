<?php
defined( 'ABSPATH' ) or die();

class AC_Column_Plugin extends AC_Column {

	public function __construct() {
		$this->set_group( __( 'Plugins', 'codepress-admin-columns' ) );
		$this->set_original( true );
	}

	public function get_value( $id ) {
		return false;
	}

}