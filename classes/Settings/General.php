<?php
namespace AC\Settings;

use AC\Capabilities;
use AC\Registrable;

class General implements Registrable {

	const SETTINGS_NAME = 'cpac_general_options';
	const SETTINGS_GROUP = 'admin-columns-general-settings';

	public function register() {
		$this->register_setting();

		add_filter( 'option_page_capability_' . self::SETTINGS_GROUP, array( $this, 'set_capability' ) );
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

}