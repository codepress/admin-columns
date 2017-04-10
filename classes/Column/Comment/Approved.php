<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_Approved extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-approved' );
		$this->set_label( __( 'Approved', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return ac_helper()->icon->yes_or_no( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_approved;
	}

}