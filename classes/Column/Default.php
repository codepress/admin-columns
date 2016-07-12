<?php
defined( 'ABSPATH' ) or die();

class AC_Column_Default extends AC_Column_OriginalAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-default';
		$this->properties['group'] = __( 'Default', 'codepress-admin-columns' );
	}

}