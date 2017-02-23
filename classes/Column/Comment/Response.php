<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends AC_Column {

	public function __construct() {
		$this->set_type( 'response' );
		$this->set_original( true );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}