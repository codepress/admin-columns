<?php

namespace AC\Column\Comment;

use AC;
use AC\Column;

/**
 * @since 2.4.2
 */
class User extends Column {

	public function __construct() {
		$this->set_type( 'column-user' );
		$this->set_label( __( 'User', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$raw_value = $this->get_raw_value( $id );

		return $this->get_formatted_value( $raw_value, $raw_value );
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
		$this->add_setting( new AC\Settings\Column\User( $this ) );
	}

}