<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Author extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'author' );
	}

	public function register_settings() {
		$this->get_settings()->width->set_default( 10 );
	}

}