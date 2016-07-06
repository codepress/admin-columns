<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'role';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;

		$this->options['width'] = 15;
	}

}