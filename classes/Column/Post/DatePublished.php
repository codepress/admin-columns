<?php

/**
 * @since 2.4
 */
class AC_Column_Post_DatePublished extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-date_published' );
		$this->set_label( __( 'Date Published' ) );
	}

	public function get_raw_value( $post_id ) {
		$post = get_post( $post_id );

		return $post->post_date;
	}

	public function register_settings() {
		$date = new AC_Settings_Column_Date( $this );
		// TODO: set default using time
		//$date->set_default( get_option( 'date_format') . ' ' . get_option( 'time_format' ) );

		$this->add_setting( $date );
	}

}