<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Nickname extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-nickname' );
		$this->set_label( __( 'Nickname', 'codepress-admin-columns' ) );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->nickname;
	}

}