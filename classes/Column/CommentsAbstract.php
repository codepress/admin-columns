<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_CommentsAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'comments';

		$this->properties['hide_label'] = true;
		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;
	}

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}