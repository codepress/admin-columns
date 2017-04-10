<?php

/**
 * @since 2.0
 */
class AC_Column_Media_ID extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-mediaid' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $id;
	}

}