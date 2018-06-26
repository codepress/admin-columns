<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class Status extends Column {

	public function __construct() {
		$this->set_type( 'column-status' );
		$this->set_label( __( 'Status', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_status', $post_id );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\StatusIcon( $this ) );
	}

}