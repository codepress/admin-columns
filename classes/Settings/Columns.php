<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Columns {

	CONST OPTIONS_KEY = 'cpac_options';

	private $storage_model_key;

	public function __construct( $storage_model_key ) {
		$this->storage_model_key = $storage_model_key;
	}

	public function get_key() {
		return apply_filters( 'ac/settings/key', self::OPTIONS_KEY . '_' . $this->storage_model_key );
	}

	// Column settings
	public function store( $columndata ) {
		return update_option( $this->get_key(), $columndata );
	}

	public function get_columns() {
		$columns = get_option( $this->get_key() );

		$columns = apply_filters( 'cpac/storage_model/stored_columns', $columns, $this->get_storage_model() );
		$columns = apply_filters( 'cpac/storage_model/stored_columns/storage_key=' . $this->storage_model_key, $columns, $this->get_storage_model() );

		if ( empty( $columns ) ) {
			return array();
		}

		return $columns;
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
		$headings = get_option( $this->get_default_key() );

		if ( empty( $headings ) ) {
			return array();
		}

		return $headings;
	}

	public function delete_default_headings() {
		delete_option( $this->get_default_key() );
	}

	// Delete all
	public static function delete_all() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '" . self::OPTIONS_KEY . "_%'" );
	}

	private function get_storage_model() {
		return AC()->get_storage_model( $this->storage_model_key );
	}

}