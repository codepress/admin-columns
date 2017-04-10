<?php

/**
 * @since 2.0
 */
class AC_Column_User_Url extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_url' );
		$this->set_label( __( 'Url', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->user_url;
	}

}