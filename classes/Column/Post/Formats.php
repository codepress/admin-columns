<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class Formats extends Column {

	public function __construct() {
		$this->set_type( 'column-post_formats' );
		$this->set_label( __( 'Post Format', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_format( $post_id );
	}

	public function get_taxonomy() {
		return 'post_format';
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\PostFormatIcon( $this ) );
	}

}