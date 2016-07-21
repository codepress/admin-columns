<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'role';

		$this->options['width'] = 15;
	}

}