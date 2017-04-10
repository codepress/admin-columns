<?php

/**
 * @since 2.4.2
 */
class AC_Column_Comment_Type extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-type' );
		$this->set_label( __( 'Type', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_type;
	}

}