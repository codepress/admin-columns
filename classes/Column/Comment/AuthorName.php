<?php

/**
 * @since 2.4.2
 */
class AC_Column_Comment_AuthorName extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-author_name' );
		$this->set_label( __( 'Author Name', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author;
	}

}