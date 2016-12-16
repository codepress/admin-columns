<?php

/**
 * @since 2.4.2
 */
class AC_Column_Comment_User extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user' );
		$this->set_label( __( 'User', 'codepress-admin-columns' ) );
	}

	/**
	 * @param int $comment_id Comment ID
	 *
	 * @return int User ID
	 */
	public function get_raw_value( $comment_id ) {
		$comment = get_comment( $comment_id );

		return $comment->user_id;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_User( $this ) );
	}

}