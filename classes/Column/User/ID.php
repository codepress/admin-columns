<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_ID extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_id' );
		$this->set_label( __( 'User ID', 'codepress-admin-columns' ) );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		return $user_id;
	}

}