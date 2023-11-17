<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class Content extends Column {

	public function __construct() {
		$this->set_type( 'column-content' );
		$this->set_label( __( 'Content', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\StringLimit( $this ) );
		$this->add_setting( new Settings\Column\BeforeAfter( $this ) );
	}

}