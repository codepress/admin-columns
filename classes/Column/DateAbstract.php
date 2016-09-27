<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_DateAbstract extends AC_Column_DefaultAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'date';

		$this->default_options['width'] = 10;
		$this->default_options['width_unit'] = '%';
	}

}