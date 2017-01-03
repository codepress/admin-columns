<?php

final class AC_Settings_ListScreen {

	CONST OPTIONS_KEY = 'cpac_options_';

	/**
	 * @var AC_ListScreen
	 */
	private $list_screen;

	/**
	 * @var array
	 */
	private $settings;

	public function __construct( AC_ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	public function get_key() {
		return apply_filters( 'ac/settings/key', $this->list_screen->get_key() );
	}

	/**
	 * Store column settings
	 *
	 * @param $settings
	 *
	 * @return bool
	 */
	public function store( $settings ) {
		return update_option( self::OPTIONS_KEY . $this->get_key(), $settings );
	}

	private function set_settings() {
		$options = get_option( self::OPTIONS_KEY . $this->get_key() );

		if ( ! $options ) {
			$options = array();
		}

		$this->settings = apply_filters( 'ac/column_settings', $options, $this->list_screen );
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->set_settings();
		}

		return $this->settings;
	}

	/**
	 * @param string $name Column name
	 *
	 * @return array
	 */
	public function get_setting( $column_name ) {
		$settings = $this->get_settings();

		return isset( $settings[ $column_name ] ) ? $settings[ $column_name ] : array();
	}

	/**
	 * @return string
	 */
	private function get_default_key() {
		return self::OPTIONS_KEY . $this->list_screen->get_key() . "__default";
	}

	public function save_default_headings( $column_headings ) {
		return update_option( $this->get_default_key(), $column_headings );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_default_headings() {
		return get_option( $this->get_default_key(), array() );
	}

	public function delete() {
		return delete_option( self::OPTIONS_KEY . $this->get_key() );
	}

	public function delete_default_headings() {
		return delete_option( $this->get_default_key() );
	}

	// todo: refactor to different name or location
	public static function delete_all_settings() {
		global $wpdb;

		$sql = "
			DELETE
			FROM $wpdb->options
			WHERE option_name LIKE %s";

		$wpdb->query( $wpdb->prepare( $sql, self::OPTIONS_KEY . '%' ) );
	}

}