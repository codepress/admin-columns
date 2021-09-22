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
		return is_plugin_active( $this->basename );
	}

	/**
	 * @return bool
	 */
	public function is_network_active() {
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