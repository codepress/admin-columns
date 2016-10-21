<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Author extends AC_Column_DefaultPostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';
	}

	public function get_default_with() {
		return 10;
	}

}