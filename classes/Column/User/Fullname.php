<?php

namespace AC\Column\User;

use AC\Column;

class Fullname extends Column {

	public function __construct() {
		$this->set_type( 'column-user_fullname' )
		     ->set_label( __( 'Fullname', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		$value = ac_helper()->user->get_display_name( $user_id, 'full_name' );

		if ( empty( $value ) ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		return get_userdata( $user_id )->user_login;
	}

}