<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Date extends AC_Column_DateAbstract {

	public function init() {
		parent::init();

		$this->options['width'] = 14;
		$this->options['width_unit'] = '%';
	}

}