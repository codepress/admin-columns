<?php

/**
 * @since 2.0
 */
class FullPath extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-full_path' );
		$this->set_label( __( 'Path', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		return wp_get_attachment_url( $id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_PathScope( $this ) );
	}

}