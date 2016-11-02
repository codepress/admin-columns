<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_LastName extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-last_name' );
		$this->set_label( __( 'Last name', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->last_name;
	}

}