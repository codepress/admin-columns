<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'parent';

		$this->default_options['width'] = 15;
		$this->default_options['width_unit'] = '%';
	}

}