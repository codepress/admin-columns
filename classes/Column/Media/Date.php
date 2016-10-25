<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Date extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'date';
		$this->properties['original'] = true;
	}

	public function get_default_with() {
		return 10;
	}

}