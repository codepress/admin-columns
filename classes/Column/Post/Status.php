<?php

class AC_Column_Post_Status extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-status' );
		$this->set_label( __( 'Status', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_status', $post_id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_StatusIcon( $this ) );
	}

}