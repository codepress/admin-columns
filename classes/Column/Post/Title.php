<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Title extends AC_ColumnPostAbstract  {

	public function init() {
		parent::init();

		$this->properties['type'] = 'title';
	}

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'title' );
	}

}