<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class Status extends Column {

	public function __construct() {
		$this->set_type( 'column-status' );
		$this->set_label( __( 'Status', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$status = $this->get_raw_value( $id );

		$statuses = $this->get_statuses();

		if ( ! isset( $statuses[ $status ] ) ) {
			return $this->get_empty_char();
		}

		return $statuses[ $status ];
	}

	public function get_statuses() {
		return [
			'trash'        => __( 'Trash' ),
			'post-trashed' => __( 'Trash' ),
			'spam'         => __( 'Spam' ),
			'1'            => __( 'Approved' ),
			'0'            => __( 'Pending' ),
		];
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_approved;
	}

}