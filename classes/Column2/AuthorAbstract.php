<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_AuthorAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;

		$this->options['width'] = 10;
		$this->options['width_unit'] = '%';
	}

}