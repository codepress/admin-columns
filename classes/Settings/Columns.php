<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class AC_Settings_Columns {

	CONST OPTIONS_KEY = 'cpac_options_';

	/**
	 * @var string $list_screen_key
	 */
	private $list_screen_key;

	/**
	 * @var array $columns
	 */
	private $columns;

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

	private function set_columns() {
		$this->columns = apply_filters( 'ac/column_settings', get_option( self::OPTIONS_KEY . $this->get_key() ), AC()->get_list_screen( $this->list_screen_key ) );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		if ( null === $this->columns ) {
			$this->set_columns();
		}

		return $this->columns;
	}

	/**
	 * @param string $name Column name
	 *
	 * @return array|false
	 */
	public function get_column( $name ) {
		$options = $this->get_columns();

		return isset( $options[ $name ] ) ? $options[ $name ] : false;
	}

	public function delete() {
		delete_option( self::OPTIONS_KEY . $this->get_key() );
	}

	// Default headings
	private function get_default_key() {
		return self::OPTIONS_KEY . $this->list_screen_key . "__default";
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