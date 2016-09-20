<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings {

	CONST OPTIONS_KEY = 'cpac_options';

	private $storage_model_key;
	private $layout_id;

	public function set_key( $storage_model_key ) {
		$this->storage_model_key = $storage_model_key;
	}

	public function set_layout( $layout_id ) {
		$this->layout_id = $layout_id ? $layout_id : false;
	}

	private function get_key() {
		return self::OPTIONS_KEY . '_' . $this->storage_model_key . $this->layout_id;
	}

	// Column settings
	public function store( $columndata ) {
		return update_option( $this->get_key(), $columndata );
	}

	public function get() {
		return get_option( $this->get_key() );
	}

	public function delete() {
		delete_option( $this->get_key() );
	}

	// Default headings
	private function get_default_key() {
		return self::OPTIONS_KEY . '_' . $this->storage_model_key . "__default";
	}

	public function store_default_headings( $column_headings ) {
		return update_option( $this->get_default_key(), $column_headings );
	}

	public function get_default_headings() {
		return get_option( $this->get_default_key() );
	}

	public function delete_default_headings() {
		delete_option( $this->get_default_key() );
	}

	// Delete all
	public static function delete_all() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '" . self::OPTIONS_KEY . "_%'" );
	}

}