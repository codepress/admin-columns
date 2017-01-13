<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Date extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'date' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Date( $this ) );

		$this->get_settings()->width->set_default( 10 );
	}

}