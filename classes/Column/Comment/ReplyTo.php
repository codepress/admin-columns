<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class ReplyTo extends Column {

	public function __construct() {
		$this->set_type( 'column-reply_to' )
		     ->set_label( __( 'In Reply To', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$parent_id = $this->get_raw_value( $id );
		$parent = get_comment( $parent_id );

		if ( ! $parent ) {
			return $this->get_empty_char();
		}

		return ac_helper()->html->link( esc_url( get_comment_link( $parent ) ), get_comment_author( $parent->comment_ID ) );
	}

	public function get_raw_value( $id ) {
		return get_comment( $id )->comment_parent;
	}

}