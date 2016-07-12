<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_DateAbstract extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'date';

		$this->options['width'] = 10;
		$this->options['width_unit'] = '%';
	}

}