<?php

namespace AC\Column\User;

use AC\Column;

class FullName extends Column {

	public function __construct() {
		$this->set_type( 'column-user_fullname' )
		     ->set_label( __( 'Full Name', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		$value = $this->get_raw_value( $user_id );

		return $value ?: $this->get_empty_char();
	}

	public function get_raw_value( $user_id ) {
		return ac_helper()->user->get_display_name( $user_id, 'full_name' );
	}

}