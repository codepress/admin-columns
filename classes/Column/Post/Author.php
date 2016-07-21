<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Author extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->options['width'] = 10;
		$this->options['width_unit'] = '%';
	}

	public function apply_conditional() {
		return in_array( $this->get_post_type(), array( 'post', 'page' ) );
	}

}