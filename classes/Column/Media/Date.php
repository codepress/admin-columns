<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Date extends AC_Column_DefaultAbstract  {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'date' );
	}

	public function get_default_with() {
		return 10;
	}

}