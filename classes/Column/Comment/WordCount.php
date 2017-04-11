<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_WordCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-word_count' );
		$this->set_label( __( 'Word Count', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		$count = $this->get_raw_value( $post_id );

		if ( ! $count ) {
			$count = ac_helper()->string->get_empty_char();
		}

		return $count;
	}

	public function get_raw_value( $post_id ) {
		$comment = get_comment( $id );

		return ac_helper()->string->word_count( $comment->comment_content );
	}

}