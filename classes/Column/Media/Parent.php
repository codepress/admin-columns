<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'parent';

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

}