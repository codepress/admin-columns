<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4
 */
class AC_Column_Post_DatePublished extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-date_published';
		$this->properties['label'] = __( 'Date Published' );
	}

	public function get_value( $post_id ) {
		$raw_value = $this->get_raw_value( $post_id );

		return $this->format->date( $raw_value ) . ' ' . ac_helper()->date->time( $raw_value );
	}

	public function get_raw_value( $post_id ) {
		$post = get_post( $post_id );

		return $post->post_date;
	}

	// TODO: add field_datetime_format
	public function display_settings() {
		$this->field_settings->date();
	}

}