<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4
 */
class AC_Column_Post_DatePublished extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-date_published' );
		$this->set_label( __( 'Date Published' ) );
	}

	public function get_value( $post_id ) {
		$raw_value = $this->get_raw_value( $post_id );

		return $this->format->date( $raw_value ) . ' ' . ac_helper()->date->time( $raw_value );
	}

	public function get_raw_value( $post_id ) {
		$post = get_post( $post_id );

		return $post->post_date;
	}

	public function display_settings() {
		$this->field_settings->date();
	}

}