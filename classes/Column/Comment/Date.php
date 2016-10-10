<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Date extends AC_Column_DateAbstract {

	public function get_default_with() {
		return 14;
	}

}