<?php

/**
 * Column displaying the number of comments for an item, displaying either the total
 * amount of comments, or the amount per status (e.g. "Approved", "Pending").
 *
 * @since 2.0
 */
class AC_Column_Post_CommentCount extends AC_Column  {

	public function __construct() {
		$this->set_type( 'column-comment_count' );
		$this->set_label( __( 'Comment count', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$value = parent::get_value( $post_id );

		if ( ! $value ) {
			return ac_helper()->string->get_empty_char();
		}

		$status = $this->get_setting( 'comment_count' )->get_value();
		$url = add_query_arg( array( 'p' => $post_id, 'comment_status' => $status ), admin_url( 'edit-comments.php' ) );

		return ac_helper()->html->link( $url, $value, array( 'class' => 'cp-' . $status ) );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function get_raw_value( $post_id ) {
		return wp_count_comments( $post_id );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_CommentCount( $this ) );
	}

}