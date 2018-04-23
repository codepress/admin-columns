<?php

namespace AC;

abstract class Plugin extends Addon {

	/**
	 * Check if plugin is network activated
	 *
	 * @return bool
	 */
	public function is_network_active() {
		return is_plugin_active_for_network( $this->get_basename() );
	}

	/**
	 * Calls get_plugin_data() for this plugin
	 *
	 * @deprecated
	 * @see get_plugin_data()
	 * @return array
	 */
	protected function get_plugin_data() {
		_deprecated_function( __METHOD__, '3.2', 'AC\Plugin::get_data()' );

		return $this->get_data();
	}

	/**
	 * Calls get_plugin_data() for this plugin
	 *
	 * @see get_plugin_data()
	 * @return array
	 */
	protected function get_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return get_plugin_data( $this->get_file(), false, false );
	}

	/**
	 * @since NEWVERSION
	 * @return false|string
	 */
	public function get_name() {
		return $this->get_plugin_header( 'Name' );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param $key
	 *
	 * @deprecated
	 * @return false|string
	 */
	protected function get_plugin_header( $key ) {
		_deprecated_function( __METHOD__, '3.2', 'AC\Plugin::get_header()' );

		return $this->get_header( $key );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param $key
	 *
	 * @return false|string
	 */
	protected function get_header( $key ) {
		$data = $this->get_data();

		if ( ! isset( $data[ $key ] ) ) {
			return false;
		}

		return $data[ $key ];
	}

	/**
	 * Return the prefix that is used by this plugin
	 *
	 * @return string
	 */
	abstract public function get_prefix();

	/**
	 * Apply updates to the database
	 *
	 * @param null|string $updates_dir
	 */
	public function install() {
		if ( 0 === version_compare( $this->get_version(), $this->get_stored_version() ) ) {
			return;
		}

		$updater = new Plugin\Updater( $this );

		if ( ! $updater->check_update_conditions() ) {
			return;
		}

		$classes = Autoloader::instance()->get_class_names_from_dir( $this->get_plugin_dir() . 'classes/Plugin/Update' );

		foreach ( $classes as $class ) {
			$updater->add_update( new $class( $this->get_stored_version() ) );
		}

		$updater->parse_updates();
	}

	/**
	 * Check if a plugin is in beta
	 *
	 * @since NEWVERSION
	 * @return bool
	 */
	public function is_beta() {
		return false !== strpos( $this->get_version(), 'beta' );
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->get_header( 'Version' );
	}

	/**
	 * @return string
	 */
	abstract protected function get_version_key();

	/**
	 * @return string
	 */
	public function get_stored_version() {
		return get_option( $this->get_version_key() );
	}

	/**
	 * Update the stored version to match the (current) version
	 */
	public function update_stored_version( $version = null ) {
		if ( null === $version ) {
			$version = $this->get_version();
		}

		return update_option( $this->get_version_key(), $version );
	}

	/**
	 * Check if the plugin was updated or is a new install
	 */
	public function is_new_install() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		return empty( $results );
	}

}