<?php

abstract class AC_Plugin {

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $stored_version;

	/**
	 * @var string
	 */
	private $plugin_dir;

	/**
	 * @var string
	 */
	private $plugin_url;

	/**
	 * @var string
	 */
	private $basename;

	/**
	 * Return the file from this plugin
	 *
	 * @return string
	 */
	abstract protected function get_file();

	/**
	 * Check if this plugin is network activated only
	 *
	 * @return bool
	 */
	public function is_network_only() {
		return is_network_only_plugin( basename( $this->get_plugin_dir() ) );
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
	 * @return string
	 */
	public function get_basename() {
		if ( null === $this->basename ) {
			$this->set_basename();
		}

		return $this->basename;
	}

	protected function set_basename() {
		$this->plugin_basename = plugin_basename( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_plugin_dir() {
		if ( null === $this->plugin_dir ) {
			$this->set_plugin_dir();
		}

		return $this->plugin_dir;
	}

	protected function set_plugin_dir() {
		$this->plugin_dir = plugin_dir_path( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_plugin_url() {
		if ( null === $this->plugin_url ) {
			$this->set_plugin_url();
		}

		return $this->plugin_url;
	}

	protected function set_plugin_url() {
		$this->plugin_url = plugin_dir_url( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_version() {
		if ( null === $this->version ) {
			$this->set_version();
		}

		return $this->version;
	}

	protected function set_version() {
		$data = $this->get_plugin_data();

		if ( isset( $data['Version'] ) ) {
			$this->version = $data['Version'];
		}
	}

	/**
	 * @return string
	 */
	abstract protected function get_version_key();

	/**
	 * @return string
	 */
	public function get_stored_version() {
		if ( null === $this->stored_version ) {
			$this->set_stored_version();
		}

		return $this->stored_version;
	}

	protected function set_stored_version() {
		$key = $this->get_version_key();

		if ( $this->is_network_only() ) {
			return get_site_option( $key );
		}

		return get_option( $key );
	}

	/**
	 * Update the stored version to match the (current) version
	 */
	public function update_stored_version() {
		$key = $this->get_version_key();

		if ( $this->is_network_only() ) {
			return update_site_option( $key, $this->get_version() );
		}

		return update_option( $key, $this->get_version() );
	}

}