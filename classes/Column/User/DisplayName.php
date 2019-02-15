<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 2.0
 */
class DisplayName extends Column {

	public function __construct() {
		$this->set_type( 'column-display_name' );
		$this->set_label( __( 'Display Name', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->display_name;
	}

}