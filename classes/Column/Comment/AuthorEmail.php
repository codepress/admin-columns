<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class AuthorEmail extends Column {

	public function __construct() {
		$this->set_type( 'column-author_email' );
		$this->set_label( __( 'Author Email', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$email = $this->get_raw_value( $id );

		return ac_helper()->html->link( $email, $email );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author_email;
	}

}