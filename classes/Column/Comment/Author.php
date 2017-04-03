<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'author' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 20 );
	}

}