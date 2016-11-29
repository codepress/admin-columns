<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_UsedByMenu extends AC_Column_UsedByMenu {

	protected function get_object_type() {
		return 'comment';
	}

}