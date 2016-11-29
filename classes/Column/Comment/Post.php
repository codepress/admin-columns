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

	public function get_value( $comment_id ) {
		return $this->get_setting( 'post' )->format( $this->get_raw_value( $comment_id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment ? $comment->comment_post_ID : false;
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_Post( $this ) );
	}

}