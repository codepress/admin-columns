<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Registered extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_registered' );
		$this->set_label( __( 'Registered', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		$user_registered = $this->get_raw_value( $user_id );

		// GMT offset is used
		return $this->get_setting( 'date' )->format( get_date_from_gmt( $user_registered ) );
	}

	public function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->user_registered;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Date( $this ) );
	}

}