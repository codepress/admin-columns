<?php

/**
 * @since 2.3.3
 */
class AC_Column_Post_EstimatedReadingTime extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-estimated_reading_time' );
		$this->set_label( __( 'Estimated Reading Time', 'codepress-admin-columns' ) );
	}

	/**
	 * Estimate read time in seconds
	 * @since 2.3.3
	 *
	 * @return int Seconds
	 */
	public function get_raw_value( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_content', $post_id );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_WordsPerMinute( $this ) );
	}

}