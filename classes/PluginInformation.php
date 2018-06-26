<?php

namespace AC;

class PluginInformation {

	/**
	 * @var string
	 */
	private $plugin_dirname;

	/**
	 * AC_Helper_Plugin constructor.
	 *
	 * @param string $plugin_dirname
	 */
	public function __construct( $plugin_dirname ) {
		$this->plugin_dirname = sanitize_key( $plugin_dirname );
	}

	/**
	 * @return string
	 */
	public function get_dirname() {
		return $this->plugin_dirname;
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
		return is_plugin_active( $this->get_plugin_var( 'Basename' ) );
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
		return $this->get_plugin_var( 'Basename' );
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
	public function get_plugin_info() {
		$plugins = (array) get_plugins();

		foreach ( $plugins as $basename => $info ) {
			if ( $this->plugin_dirname === dirname( $basename ) ) {
				$info['Basename'] = $basename;

				return $info;
			}
		}

		return false;
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