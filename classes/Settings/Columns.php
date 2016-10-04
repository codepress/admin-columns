<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class AC_Settings_Columns {

	CONST OPTIONS_KEY = 'cpac_options_';

	private $list_screen_key;

	public function __construct( $list_screen_key ) {
		$this->list_screen_key = $list_screen_key;
	}

	public function get_key() {
		return apply_filters( 'ac/settings/key', $this->list_screen_key );
	}

	// Column settings
	public function store( $columndata ) {
		return update_option( self::OPTIONS_KEY . $this->get_key(), $columndata );
	}

	public function get_columns() {

		// TODO: lazy load?

		$columns = get_option( self::OPTIONS_KEY . $this->get_key() );

		$columns = apply_filters( 'ac/column_settings', $columns, AC()->get_list_screen( $this->list_screen_key ) );

		return $columns ? $columns : array();
	}

	public function delete() {
		delete_option( $this->get_key() );
	}

	// Default headings
	private function get_default_key() {
		return self::OPTIONS_KEY . '_' . $this->list_screen_key . "__default";
	}

	public function store_default_headings( $column_headings ) {
		return update_option( $this->get_default_key(), $column_headings );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_default_headings() {
		$headings = get_option( $this->get_default_key() );

		return $headings ? $headings : array();
	}

	public function delete_default_headings() {
		delete_option( $this->get_default_key() );
	}

	// Delete all
	public static function delete_all() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '" . self::OPTIONS_KEY . "%'" );
	}

}