<?php

namespace AC;

use AC\Plugin\Version;

class PluginInformation {

	/**
	 * @var string
	 */
	private $basename;

	public function __construct( $basename ) {
		$this->basename = (string) $basename;
	}

	public static function create_by_file( $file ) {
		return new self( plugin_basename( $file ) );
	}

	/**
	 * @return string
	 */
	public function get_basename() {
		return $this->basename;
	}

	/**
	 * @return string
	 */
	public function get_dirname() {
		return dirname( $this->basename );
	}

	/**
	 * @return bool
	 */
	public function is_installed() {
		return null !== $this->get_header_data();
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( $this->basename );
	}

	/**
	 * @return bool
	 */
	public function is_network_active() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active_for_network( $this->basename );
	}

	/**
	 * @return Version
	 */
	public function get_version() {
		return new Version( (string) $this->get_header( 'Version' ) );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->get_header( 'Name' );
	}

	/**
	 * @return array
	 */
	private function get_plugins() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		// use `get_plugins` (cached) over `get_plugin_data` (non cached)
		return (array) get_plugins();
	}

	/**
	 * @return array
	 */
	private function get_plugin_updates() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return get_plugin_updates();
	}

	/**
	 * @return object|null
	 */
	private function get_update_data() {
		$update_data = $this->get_plugin_updates();

		if ( ! array_key_exists( $this->basename, $update_data ) ) {
			return null;
		}

		return $update_data[ $this->basename ]->update;
	}

	/**
	 * @return bool
	 */
	public function has_update_version() {
		$update_data = $this->get_update_data();

		return null !== $update_data;
	}

	/**
	 * @return Version
	 */
	public function get_update_version() {
		$update_data = $this->get_update_data();

		return new Version( $update_data->new_version );
	}

	/**
	 * @return bool
	 */
	public function has_update_package() {
		$update_data = $this->get_update_data();

		return false !== $update_data->package;
	}

	/**
	 * @return string
	 */
	public function get_update_package() {
		$update_data = $this->get_update_data();

		return (string) $update_data->package;
	}

	/**
	 * @return array|null
	 */
	private function get_header_data() {
		$plugins = $this->get_plugins();

		if ( ! array_key_exists( $this->basename, $plugins ) ) {
			return null;
		}

		return $plugins[ $this->basename ];
	}

	/**
	 * @param string $var
	 *
	 * @return string|null
	 */
	public function get_header( $var ) {
		$info = $this->get_header_data();

		return $info && isset( $info[ $var ] )
			? (string) $info[ $var ]
			: null;
	}

}