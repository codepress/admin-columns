<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Date extends AC_Column_DefaultPostAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'date' );
	}

	public function get_default_with() {
		return 10;
	}

}