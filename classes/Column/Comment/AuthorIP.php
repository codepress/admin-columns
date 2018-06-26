<?php

namespace AC\Column\Comment;

use AC\Column;

class AuthorIP extends Column {

	public function __construct() {
		$this->set_type( 'column-author_ip' );
		$this->set_label( __( 'Author IP', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author_IP;
	}

}