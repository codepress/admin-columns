<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Author extends AC_Column_DefaultPost {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'author' );
	}

	public function get_default_with() {
		return 10;
	}

}