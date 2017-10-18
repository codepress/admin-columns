<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_Agent extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-agent' );
		$this->set_label( __( 'Agent', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_agent;
	}

}