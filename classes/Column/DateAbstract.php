<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_DateAbstract extends AC_Column_DefaultAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'date';

		$this->set_default_option( 'width', 10 );
	}

}