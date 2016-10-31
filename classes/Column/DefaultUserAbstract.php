<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultUserAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Default', 'codepress-admin-columns' );
		$this->properties['original'] = true;
	}

}