<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Author extends AC_Column_DefaultPostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'author';

		$this->set_default_option( 'width', 10 );
	}

	public function apply_conditional() {
		return in_array( $this->get_post_type(), array( 'post', 'page' ) );
	}

}