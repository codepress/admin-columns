<?php

namespace AC\Column\Media;

use AC\Column;

abstract class Meta extends Column\Meta {

	public function __construct() {
		$this->set_serialized( true );
	}

	public function get_meta_key() {
		return '_wp_attachment_metadata';
	}

	public function get_raw_value( $id ) {
		return $this->get_meta_value( $id, $this->get_meta_key() );
	}

}