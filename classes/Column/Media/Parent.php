<?php

/**
 * @since 3.0
 */
class AC_Column_Media_Parent extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'parent' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}