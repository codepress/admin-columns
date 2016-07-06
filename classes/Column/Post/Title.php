<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Title extends AC_Column_TitleAbstract {

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'title' );
	}

}