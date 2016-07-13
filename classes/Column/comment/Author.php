<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->options['width'] = 20;
		$this->options['width_unit'] = '%';
	}

}