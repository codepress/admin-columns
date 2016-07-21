<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_TitleAbstract extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'title';
	}

}