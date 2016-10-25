<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'response';
		$this->properties['original'] = true;
	}

	public function get_default_with() {
		return 15;
	}

}