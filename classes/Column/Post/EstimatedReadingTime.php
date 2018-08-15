<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.3.3
 */
class EstimatedReadingTime extends Column {

	public function __construct() {
		$this->set_type( 'column-estimated_reading_time' );
		$this->set_label( __( 'Estimated Reading Time', 'codepress-admin-columns' ) );
	}

	/**
	 * Estimate read time in seconds
	 * @since 2.3.3
	 *
	 * @param $post_id
	 *
	 * @return string Raw Post Content
	 */
	public function get_raw_value( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_content', $post_id );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\WordsPerMinute( $this ) );
	}

}