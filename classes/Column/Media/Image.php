<?php

namespace AC\Column\Media;

use AC;

class Image extends AC\Column {

	public function __construct() {
		$this->set_type( 'column-image' );
		$this->set_label( __( 'Image', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		return $id;
	}

	public function register_settings() {
		$this->add_setting( new AC\Settings\Column\Image( $this ) );
	}

}