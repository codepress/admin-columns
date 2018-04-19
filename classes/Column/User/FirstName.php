<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 2.0
 */
class FirstName extends Column\Meta {

	public function __construct() {
		$this->set_type( 'column-first_name' );
		$this->set_label( __( 'First Name', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return 'first_name';
	}

	public function get_value( $user_id ) {
		$value = $this->get_raw_value( $user_id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		return get_user_meta( $user_id, $this->get_meta_key(), true );
	}

}