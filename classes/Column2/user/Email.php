<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_Email extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'email';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;
	}

}