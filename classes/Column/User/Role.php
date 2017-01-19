<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Role extends AC_Column_Meta {

	public function __construct() {

		$this->set_type( 'role' );
		$this->set_group( 'default' );
		$this->set_original( true );
	}

	public function get_meta_key() {
		global $wpdb;

		return $wpdb->get_blog_prefix() . 'capabilities'; // WPMU compat
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}