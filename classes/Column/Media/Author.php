<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Author extends AC_Column_Post_Author {

	public function apply_conditional() {
		$post_type = method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;

		return in_array( $post_type, array( 'attachment' ) );
	}
}