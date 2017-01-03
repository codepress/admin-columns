<?php

/**
 * @since 2.0
 */
class AC_Column_User_Description extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	public function get_raw_value( $user_id ) {
		return $this->get_setting( 'word_limit' )->format( get_the_author_meta( 'user_description', $user_id ) );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_WordLimit( $this ) );
	}

}