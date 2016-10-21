<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_DefaultUserAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'role';
	}

	public function get_default_with() {
		return 15;
	}

}