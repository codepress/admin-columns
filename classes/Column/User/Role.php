<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'role' );
	}

	public function register_settings() {
		$this->get_settings()->width->set_default( 15 );
	}

}