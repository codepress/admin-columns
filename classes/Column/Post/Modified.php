<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Modified extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-modified' );
		$this->set_label( __( 'Last modified', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_modified', $post_id );
	}

	public function register_settings() {
		$date = new AC_Settings_Setting_Date( $this );
		$date->set_default( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

		$this->add_setting( $date );
	}

}