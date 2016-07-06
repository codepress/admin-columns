<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Categories extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'categories';

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;

		$this->options['width'] = 15;
		$this->options['width_unit'] = '%';
	}

	public function apply_conditional() {
		return in_array( $this->get_post_type(), array( 'post', 'page' ) );
	}

}