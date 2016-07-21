<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Author extends AC_Column_Post_Author {

	public function apply_conditional() {
		return in_array( $this->get_post_type(), array( 'attachment' ) );
	}
}