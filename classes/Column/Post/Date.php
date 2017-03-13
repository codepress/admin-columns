<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Date extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'date' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Date( $this ) );

		$this->get_setting( 'width' )->set_default( 10 );
	}

}