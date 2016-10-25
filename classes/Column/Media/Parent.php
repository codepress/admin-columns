<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends AC_Column_DefaultPostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'parent';
		$this->properties['original'] = true;
	}

	public function get_default_with() {
		return 15;
	}

}