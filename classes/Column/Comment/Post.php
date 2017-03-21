<?php

/**
 * @since 2.4.7
 */
class AC_Column_Comment_Post extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-post' );
		$this->set_label( __( 'Post', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		if ( ! $comment ) {
			return false;
		}

		return $comment->comment_post_ID;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Post( $this ) );
	}

}