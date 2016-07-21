<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Categories extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'categories';

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

	public function get_taxonomy() {
		return 'category';
	}

	public function apply_conditional() {
		return ac_helper()->taxonomy->is_taxonomy_registered( $this->get_post_type(), $this->get_taxonomy() );
	}

}