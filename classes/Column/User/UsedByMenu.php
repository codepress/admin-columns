<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since NEWVERSION
 */
class AC_Column_User_UsedByMenu extends AC_Column_UsedByMenuAbstract {

	protected function get_object_type() {
		return 'user';
	}

}