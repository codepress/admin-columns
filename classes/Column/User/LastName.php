<?php

/**
 * @since 2.0
 */
class AC_Column_User_LastName extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-last_name' );
		$this->set_label( __( 'Last name', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return 'last_name';
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		return $this->get_meta_value( $user_id, $this->get_meta_key() );
	}

}