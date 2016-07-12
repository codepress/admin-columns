<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_AuthorAbstract extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->options['width'] = 10;
		$this->options['width_unit'] = '%';
	}

}