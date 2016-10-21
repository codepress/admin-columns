<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';
		$this->properties['original'] = true;
	}

	public function get_default_with() {
		return 20;
	}

}