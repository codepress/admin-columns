<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.3.3
 */
class AC_Column_Post_EstimatedReadingTime extends AC_Column_PostAbstract {

	public function __construct() {
		$this->set_type( 'column-estimated_reading_time' );
		$this->set_label( __( 'Estimated Reading Time', 'codepress-admin-columns' ) );
	}

	/**
	 * Estimate read time in readable format
	 * @since 2.3.3
	 */
	public function get_value( $post_id ) {
		return $this->format->words_per_minute( get_post_field( 'post_content', $post_id ) );
	}

	/**
	 * Estimate read time in seconds
	 * @since 2.3.3
	 */
	public function get_raw_value( $post_id ) {
		return $this->format->words_per_minute( get_post_field( 'post_content', $post_id ), false );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

	public function display_settings() {
		$this->field_settings->words_per_minute();
	}
}