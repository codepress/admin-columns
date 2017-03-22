<?php

/**
 * @since 2.0
 */
class AC_Column_User_Registered extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_registered' );
		$this->set_label( __( 'Registered', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->format_value( get_date_from_gmt( $this->get_raw_value( $user_id ) ) );
	}

	public function get_raw_value( $user_id ) {
		return get_userdata( $user_id )->user_registered;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Date( $this ) );
	}

}