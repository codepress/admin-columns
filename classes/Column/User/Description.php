<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class Description extends Column\Meta {

	public function __construct() {
		$this->set_type( 'column-user_description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return 'description';
	}

	public function get_raw_value( $user_id ) {
		return get_the_author_meta( 'user_description', $user_id );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\WordLimit( $this ) );
		$this->add_setting( new Settings\Column\BeforeAfter( $this ) );
	}

}