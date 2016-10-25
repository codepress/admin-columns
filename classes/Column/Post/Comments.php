<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Comments extends AC_Column_DefaultPostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'comments';
		$this->properties['hide_label'] = true;
	}

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}