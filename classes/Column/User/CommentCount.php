<?php

/**
 * @since 2.0
 */
class AC_Column_User_CommentCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_commentcount' );
		$this->set_label( __( 'Comment Count', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		return get_comments( array(
			'user_id' => $user_id,
			'count'   => true,
			'orderby' => false,
		) );
	}

}