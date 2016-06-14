<?php
defined( 'ABSPATH' ) or die();

class CPAC_Column_WP_Plugin extends CPAC_Column_WP_Default {

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Columns by Plugins', 'codepress-admin-columns' );
	}
}