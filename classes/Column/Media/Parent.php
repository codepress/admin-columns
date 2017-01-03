<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends AC_Column_DefaultPost {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'parent' );
	}

	public function register_settings() {
		$this->get_settings()->width->set_default( 15 );
	}

}