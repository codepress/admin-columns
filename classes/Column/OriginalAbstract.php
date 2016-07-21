<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_OriginalAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;
	}

}