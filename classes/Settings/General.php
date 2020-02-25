<?php

namespace AC\Settings;

use AC\Capabilities;
use AC\Registrable;

class General implements Registrable {

	const SETTINGS_NAME = 'cpac_general_options';
	const SETTINGS_GROUP = 'admin-columns-general-settings';

	public function register() {
		$this->register_setting();

		add_filter( 'option_page_capability_' . self::SETTINGS_GROUP, [ $this, 'set_capability' ] );
	}

	public function register_setting() {
		register_setting( self::SETTINGS_GROUP, self::SETTINGS_NAME );
	}

	/**
	 * @return string
	 */
	public function set_capability() {
		return Capabilities::MANAGE;
	}

	/**
	 * @return mixed
	 */
	public function get_options() {
		return get_option( self::SETTINGS_NAME );
	}

	public function get_name() {
		return self::SETTINGS_NAME;
	}

	/**
	 * @return bool
	 */
	public function is_empty() {
		return false === $this->get_options();
	}

	/**
	 * @param string $key
	 *
	 * @return false|mixed
	 */
	public function get_option( $key ) {
		$options = $this->get_options();

		if ( ! $options || ! array_key_exists( $key, $options ) ) {
			return false;
		}

		return $options[ $key ];
	}

	/**
	 * Delete all options
	 */
	public function delete_options() {
		delete_option( self::SETTINGS_NAME );
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 */
	public function save_option( $name, $value ) {
		$options = $this->get_options();

		if ( false === $options ) {
			$options = [];
		}

		$options[ $name ] = $value;

		update_option( self::SETTINGS_NAME, $options );
	}

	/**
	 * @param string $name
	 */
	public function delete_option( $name ) {
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return;
		}

		unset( $options[ $name ] );

		update_option( self::SETTINGS_NAME, $options );
	}

}