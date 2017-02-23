<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Email extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'email' );
	}

}