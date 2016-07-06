<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Parent extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'parent';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

}