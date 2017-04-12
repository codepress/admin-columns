<?php

/**
 * Column displaying the number of comments for an item, displaying either the total
 * amount of comments, or the amount per status (e.g. "Approved", "Pending").
 *
 * @since 2.0
 */
class AC_Column_Post_CommentCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-comment_count' );
		$this->set_label( __( 'Comment Count', 'codepress-admin-columns' ) );
		$this->set_empty_char( true );
	}

	public function get_value( $id ) {
		return $this->get_formatted_value( $id );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_CommentCount( $this ) );
	}

}