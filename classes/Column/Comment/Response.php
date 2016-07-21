<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'response';

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

}