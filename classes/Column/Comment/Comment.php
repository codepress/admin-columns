<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Comment extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'comment';
		$this->properties['original'] = true;
	}

}