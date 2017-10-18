<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_Excerpt extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Content', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_content;
	}

	public function register_settings() {
		$word_limit = new AC_Settings_Column_WordLimit( $this );
		$word_limit->set_default( 15 );

		$this->add_setting( $word_limit );
	}

}