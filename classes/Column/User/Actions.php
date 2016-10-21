<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Actions extends AC_Column_ActionsAbstract {

	protected function get_object_type() {
		return 'user';
	}

}