<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class Modified extends Column {

	public function __construct() {
		$this->set_type( 'column-modified' );
		$this->set_label( __( 'Last Modified', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_modified', $post_id );
	}

	public function register_settings() {
		$date = new Settings\Column\Date( $this );
		$date->set_default( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

		$this->add_setting( $date );
	}

}