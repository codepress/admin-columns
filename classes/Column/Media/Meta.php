<?php

abstract class AC_Column_Media_Meta extends AC_Column_Meta {

	public function __construct() {
		$this->set_serialized( true );
		$this->set_empty_char( true );
	}

	public function get_meta_key() {
		return '_wp_attachment_metadata';
	}

	public function get_raw_value( $id ) {
		return $this->get_meta_value( $id, $this->get_meta_key() );
	}

}