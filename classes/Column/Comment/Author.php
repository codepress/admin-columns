<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends AC_Column_DefaultAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'author' );
	}

	public function get_default_with() {
		return 20;
	}

}