<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class AuthorUrl extends Column {

	public function __construct() {
		$this->set_type( 'column-author_url' );
		$this->set_label( __( 'Author URL', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return ac_helper()->string->shorten_url( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author_url;
	}

}