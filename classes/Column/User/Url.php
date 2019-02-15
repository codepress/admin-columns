<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 2.0
 */
class Url extends Column {

	public function __construct() {
		$this->set_type( 'column-user_url' );
		$this->set_label( __( 'Website', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		return get_userdata( $user_id )->user_url;
	}

}