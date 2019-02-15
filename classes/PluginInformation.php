<?php

namespace AC;

class PluginInformation {

	/**
	 * @var string
	 */
	private $basename;

	public function __construct( $basename ) {
		$this->basename = $basename;
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
		return $this->get_plugin_info() ? true : false;
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		return is_plugin_active( $this->basename );
	}

	/**
	 * @return string|false Returns the plugin version if the plugin is installed, false otherwise
	 */
	public function get_version() {
		return $this->get_plugin_var( 'Version' );
	}

	/**
	 * @return string Basename
	 */
	public function get_basename() {
		return $this->basename;
	}

	/**
	 * @return string Name
	 */
	public function get_name() {
		return $this->get_plugin_var( 'Name' );
	}

	/**
	 * @return array|false
	 */
	private function get_plugin_info() {
		$plugins = (array) get_plugins();

		if ( ! array_key_exists( $this->basename, $plugins ) ) {
			return false;
		}

		return $plugins[ $this->basename ];
	}

	/**
	 * @param string $var
	 *
	 * @return string|false
	 */
	public function get_plugin_var( $var ) {
		$info = $this->get_plugin_info();

		if ( ! isset( $info[ $var ] ) ) {
			return false;
		}

		return $info[ $var ];
	}

}