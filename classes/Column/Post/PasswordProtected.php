<?php

namespace AC\Column\Post;

use AC\Column;

class PasswordProtected extends Column {

	public function __construct() {
		$this->set_type( 'column-password_protected' );
		$this->set_label( __( 'Password Protected', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$raw_value = $this->get_raw_value( $post_id );

		if ( ! $raw_value ) {
			return $this->get_empty_char();
		}

		$tooltip = sprintf( '<strong>%s</strong>: %s', __( 'Password', 'codepress-admin-columns' ), $raw_value );

		return ac_helper()->icon->yes( $tooltip );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_password', $post_id, 'raw' );
	}

}