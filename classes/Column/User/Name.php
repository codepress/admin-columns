<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Name extends AC_Column_DefaultAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'name' );
	}

}