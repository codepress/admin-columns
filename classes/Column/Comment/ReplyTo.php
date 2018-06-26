<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class ReplyTo extends Column {

	public function __construct() {
		$this->set_type( 'column-reply_to' );
		$this->set_label( __( 'In Reply To', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = '';
		$parent = $this->get_raw_value( $id );
		if ( $parent ) {
			$parent = get_comment( $parent );

			$value = ac_helper()->html->link( esc_url( get_comment_link( $parent ) ), get_comment_author( $parent->comment_ID ) );
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_parent;
	}

}