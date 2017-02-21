<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'response' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}