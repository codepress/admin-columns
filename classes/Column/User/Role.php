<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_DefaultAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'role';

		$this->set_default_option( 'width', 15 );
	}

}