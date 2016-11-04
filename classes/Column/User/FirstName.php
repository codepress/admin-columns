<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_FirstName extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-first_name' );
		$this->set_label( __( 'First name', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->first_name;
	}

}