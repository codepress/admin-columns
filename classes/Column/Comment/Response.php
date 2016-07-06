<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'response';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

}