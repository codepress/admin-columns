<?php

/**
 * Column displaying full item permalink (including URL).
 *
 * @since 2.0
 */
class AC_Column_Post_Permalink extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-permalink' );
		$this->set_label( __( 'Permalink', 'codepress-admin-columns' ) );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_LinkToPost( $this ) );
	}

}