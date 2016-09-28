<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends AC_Column_DefaultAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->set_default_option( 'width', 20 );
	}

}