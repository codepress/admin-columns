<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends AC_Column_DefaultPost {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'parent' );
	}

	public function get_default_with() {
		return 15;
	}

}