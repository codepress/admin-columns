<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_Response extends AC_Column_DefaultAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'response';

		$this->set_default_option( 'width', 15 );
	}

}