<?php
defined( 'ABSPATH' ) or die();

class AC_Column_Default extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['default'] = true;
		$this->properties['is_cloneable'] = false;
	}

}