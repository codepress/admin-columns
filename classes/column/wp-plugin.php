<?php
defined( 'ABSPATH' ) or die();

class CPAC_Column_WP_Plugin extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Columns by Plugins', 'codepress-admin-columns' );
		$this->properties['default'] = true;
		$this->properties['is_cloneable'] = false;
	}
}