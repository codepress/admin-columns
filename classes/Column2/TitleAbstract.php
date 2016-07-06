<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_TitleAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'title';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;
	}

}