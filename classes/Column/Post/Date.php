<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Date extends AC_Column_DefaultPost {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'date' );
	}

	public function get_default_with() {
		return 10;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Date( $this ) );
	}

}