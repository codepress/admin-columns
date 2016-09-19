<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings {

	CONST OPTIONS_KEY = 'cpac_options';

	private $storage_model_key;
	private $layout_id;

	public function __construct( $storage_model_key, $layout ) {
		$this->storage_model_key = $storage_model_key;
		$this->layout_id = $layout ? $layout : false; // no zero's allowed
	}

	private function get_storage_key() {
		return self::OPTIONS_KEY . '_' . $this->storage_model_key . $this->layout_id;
	}

	// Column settings
	public function store( $columndata ) {
		return update_option( $this->get_storage_key(), $columndata );
	}

	public function get() {
		return get_option( $this->get_storage_key() );
	}

	public function delete() {
		delete_option( $this->get_storage_key() );
	}

	// Default headings
	public function store_default_headings( $column_headings ) {
		return update_option( $this->get_storage_key() . "__default", $column_headings );
	}

	public function get_default_headings() {
		return get_option( $this->get_storage_key() . "__default" );
	}

	public function delete_default_headings() {
		delete_option( $this->get_storage_key() . "__default" );
	}

}