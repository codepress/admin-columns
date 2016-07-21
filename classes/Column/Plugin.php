<?php
defined( 'ABSPATH' ) or die();

class AC_Column_Plugin extends AC_Column_OriginalAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-plugin';
		$this->properties['group'] = __( 'Plugins', 'codepress-admin-columns' );
	}

}