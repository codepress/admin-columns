<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Registered extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_registered';
		$this->properties['label'] = __( 'Registered', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		$user_registered = $this->get_raw_value( $user_id );

		// GMT offset is used
		return $this->get_date( get_date_from_gmt( $user_registered ), $this->get_option( 'date_format' ) );
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->user_registered;
	}

	function display_settings() {
		$this->display_field_date_format();
	}

}