<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends AC_Column_AuthorAbstract {

	public function init() {
		parent::init();

		$this->options['width'] = 20;
		$this->options['width_unit'] = '%';
	}

}