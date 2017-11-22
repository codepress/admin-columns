<?php

abstract class AC_Plugin extends AC_Addon {

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
	 * @see get_plugin_data()
	 * @return array
	 */
	protected function get_plugin_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return get_plugin_data( $this->get_file(), false, false );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param $key
	 *
	 * @return false|string
	 */
	protected function get_plugin_header( $key ) {
		$data = $this->get_plugin_data();

		if ( ! isset( $data[ $key ] ) ) {
			return false;
		}

		return $data[ $key ];
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->get_plugin_header( 'Version' );
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
	public function update_stored_version( $version ) {
		return update_option( $this->get_version_key(), $version );
	}

	/**
	 * Check if the plugin was updated or is a fresh install
	 */
	public function is_fresh_install() {
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