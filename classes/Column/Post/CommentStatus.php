<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * Column displaying whether an item is open for comments, i.e. whether users can
 * comment on this item.
 * @since 2.0
 */
class CommentStatus extends Column {

	public function __construct() {
		$this->set_type( 'column-comment_status' );
		$this->set_label( __( 'Allow Comments', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	public function get_value( $post_id ) {
		$comment_status = $this->get_raw_value( $post_id );

		return ac_helper()->icon->yes_or_no( ( 'open' === $comment_status ), $comment_status );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'comment_status', $post_id, 'raw' );
	}

}