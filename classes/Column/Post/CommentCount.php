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

	/**
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public function get_value( $post_id ) {
		return $this->get_settings()->comment_status->format( $post_id );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function get_raw_value( $post_id ) {
		$value = '';

		$status = $this->get_option( 'comment_status' );
		$count = wp_count_comments( $post_id );

		if ( isset( $count->{$status} ) ) {
			$value = $count->{$status};
		}

		return $value;
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_CommentStatus( $this ) );
	}

}