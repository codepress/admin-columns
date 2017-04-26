<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_WordCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-word_count' );
		$this->set_label( __( 'Word Count', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		$comment = get_comment( $id );

		return ac_helper()->string->word_count( $comment->comment_content );
	}

}