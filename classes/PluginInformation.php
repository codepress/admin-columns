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
		return null !== $this->get_plugin_info();
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
	 * @return string|null
	 */
	public function get_version() {
		return $this->get_plugin_var( 'Version' );
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
	public function get_name() {
		return $this->get_plugin_var( 'Name' );
	}

	/**
	 * @return array|null
	 */
	private function get_plugin_info() {
		$plugins = (array) get_plugins();

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
	private function get_plugin_var( $var ) {
		$info = $this->get_plugin_info();

		if ( ! $info || ! isset( $info[ $var ] ) ) {
			return null;
		}

		return $info[ $var ];
	}

	/**
	 * @param string $action 'activate' or 'deactivate'
	 * @param string $basename
	 *
	 * @return string
	 */
	public function get_plugin_action_url( $action ) {
		return add_query_arg( [
			'action' => $action,
			'plugin' => $this->basename,
		], wp_nonce_url( admin_url( 'plugins.php' ), $action . '-plugin_' . $this->basename ) );
	}

	/**
	 * @param string $action 'activate' or 'deactivate'
	 * @param string $basename
	 *
	 * @return string
	 */
	public function get_plugin_network_action_url( $action ) {
		return add_query_arg( [
			'action' => $action,
			'plugin' => $this->basename,
		], wp_nonce_url( network_admin_url( 'plugins.php' ), $action . '-plugin_' . $this->basename ) );
	}

}