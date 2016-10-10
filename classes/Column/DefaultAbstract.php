<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-default';
		$this->properties['group'] = __( 'Default', 'codepress-admin-columns' );

		$this->properties['original'] = true;
	}

}