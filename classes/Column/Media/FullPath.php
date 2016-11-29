<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_FullPath extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-full_path' );
		$this->set_label( __( 'Full path', 'codepress-admin-columns' ) );
	}

	// Display
	public function get_value( $id ) {
		return $this->get_setting( 'path_scope' )->format( wp_get_attachment_url( $id ) );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_PathScope( $this ) );
	}

}