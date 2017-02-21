<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Date extends AC_Column_Default  {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'date' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 10 );
	}

}