<?php
defined( 'ABSPATH' ) or die();

class AC_Column_Plugin extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Plugins', 'codepress-admin-columns' );
		$this->properties['original'] = true;
	}

}