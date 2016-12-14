<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Email extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'email' );
	}

}