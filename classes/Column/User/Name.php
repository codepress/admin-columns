<?php

/**
 * @since 3.0
 */
class AC_Column_User_Name extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'name' );
	}

}