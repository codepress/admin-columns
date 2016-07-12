<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
abstract class AC_Column_CommentsAbstract extends AC_Column_Default {

	public function init() {
		parent::init();

		$this->properties['type'] = 'comments';
		$this->properties['hide_label'] = true;
	}

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}