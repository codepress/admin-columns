<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'role' );
	}

	public function get_default_with() {
		return 15;
	}

}