<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends AC_Column_DefaultAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'response' );
	}

	public function get_default_with() {
		return 15;
	}

}