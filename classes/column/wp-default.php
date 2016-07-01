<?php
defined( 'ABSPATH' ) or die();

class CPAC_Column_WP_Default extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['default'] = true;
		$this->properties['is_cloneable'] = false;
	}

}