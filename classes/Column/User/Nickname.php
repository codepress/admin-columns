<?php

/**
 * @since 2.0
 */
class AC_Column_User_Nickname extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-nickname' );
		$this->set_label( __( 'Nickname', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return 'nickname';
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		return $this->get_meta_value( $user_id, $this->get_meta_key() );
	}

}