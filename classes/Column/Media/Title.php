<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Title extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'title';
		$this->properties['original'] = true;
	}

}