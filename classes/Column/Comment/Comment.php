<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Comment extends AC_Column_DefaultAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'comment' );
	}

}