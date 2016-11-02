<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Excerpt extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Content', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->format->word_limit( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_content;
	}

	public function display_settings() {
		$this->field_settings->word_limit( 15 );
	}

}