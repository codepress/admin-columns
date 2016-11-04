<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.7
 */
class AC_Column_Comment_Post extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-post' );
		$this->set_label( __( 'Post', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$post_id = $this->get_raw_value( $id );

		return ac_helper()->html->link( $this->format->post_link_to( $post_id ), $this->format->post( $post_id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment ? $comment->comment_post_ID : false;
	}

	public function display_settings() {
		$this->field_settings->post();
		$this->field_settings->post_link_to();
	}

}