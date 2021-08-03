<?php

namespace AC;

use AC\Asset\Location;
use AC\Plugin\Version;
use ReflectionObject;
use WP_Roles;

abstract class Plugin {

	/**
	 * @var Installer|null
	 */
	private $installer;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * Return the file from this plugin
	 * @return string
	 */
	abstract protected function get_file();

	/**
	 * @return string
	 */
	public function get_basename() {
		return plugin_basename( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_dir() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return plugin_dir_url( $this->get_file() );
	}

	public function set_installer( Installer $installer ) {
		$this->installer = $installer;
	}

	/**
	 * Check if plugin is network activated
	 * @return bool
	 */
	public function is_network_active() {
		return is_plugin_active_for_network( $this->get_basename() );
	}

	/**
	 * Calls get_plugin_data() for this plugin
	 * @return array
	 * @see get_plugin_data()
	 */
	protected function get_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $this->data ) {
			$this->data = get_plugin_data( $this->get_file(), false, false );
		}

		return $this->data;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return (string) $this->get_header( 'Name' );
	}

	/**
	 * Return a plugin header from the plugin data
	 *
	 * @param string $key
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
	 * @return bool
	 */
	private function can_install() {

		// Run installer manually
		if ( '1' === filter_input( INPUT_GET, 'ac-force-install' ) ) {
			return true;
		}

		// Run installer when the current version is not equal to its stored version
		if ( $this->get_version()->is_not_equal( $this->get_stored_version() ) ) {
			return true;
		}

		// Run installer when the current version can not be read from the plugin's header file
		if ( ! $this->get_version()->get_value() && ! $this->get_stored_version()->get_value() ) {
			return true;
		}

		return false;
	}

	public function install() {
		if ( ! $this->can_install() ) {
			return;
		}

		global $wp_roles;

		if ( ! $wp_roles ) {
			$wp_roles = new WP_Roles();
		}

		do_action( 'ac/capabilities/init', $wp_roles );

		if ( $this->installer instanceof Installer ) {
			$this->installer->install();
		}

		if ( current_user_can( Capabilities::MANAGE ) && ! is_network_admin() ) {
			$this->run_updater();
		}
	}

	private function run_updater() {
		$updater = new Plugin\Updater\Site( $this );

		$reflection = new ReflectionObject( $this );
		$classes = Autoloader::instance()->get_class_names_from_dir( $reflection->getNamespaceName() . '\Plugin\Update' );

		foreach ( $classes as $class ) {
			$updater->add_update( new $class( $this->get_stored_version()->get_value() ) );
		}

		$updater->parse_updates();
	}

	/**
	 * @return Location\Absolute
	 */
	public function get_location() {
		return new Location\Absolute(
			$this->get_url(),
			$this->get_dir()
		);
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
	abstract protected function get_version_key();

	/**
	 * @param string $version
	 *
	 * @return bool
	 */
	public function is_version_gte( $version ) {
		return $this->get_version()->is_gte( new Version( $version ) );
	}

	/**
	 * @return Version
	 */
	public function get_stored_version() {
		return new Version( (string) get_option( $this->get_version_key() ) );
	}

	/**
	 * Update the stored version to match the (current) version
	 *
	 * @param null $version
	 *
	 * @return bool
	 */
	public function update_stored_version( $version = null ) {
		if ( null === $version ) {
			$version = $this->get_version()->get_value();
		}

		return update_option( $this->get_version_key(), $version, false );
	}

	/**
	 * Check if the plugin was updated or is a new install
	 */
	public function is_new_install() {
		global $wpdb;

		if ( $this->get_stored_version() ) {
			return false;
		}

		// Before version 3.0.5
		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		return empty( $results );
	}

}