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
	 * @var bool
	 */
	private $fresh_install;

	/**
	 * Return the file from this plugin
	 *
	 * @return string
	 */
	abstract protected function get_file();

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
	 * @return string
	 */
	public function get_basename() {
		if ( null === $this->basename ) {
			$this->set_basename();
		}

		return $this->basename;
	}

	protected function set_basename() {
		$this->basename = plugin_basename( $this->get_file() );
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
		$this->stored_version = get_option( $this->get_version_key() );
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
		if ( null === $this->fresh_install ) {
			$this->set_fresh_install();
		}

		return $this->fresh_install;
	}

	protected function set_fresh_install() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		$this->fresh_install = empty( $results );
	}

}