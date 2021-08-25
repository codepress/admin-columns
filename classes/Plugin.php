<?php

namespace AC;

use AC\Asset\Location;
use AC\Plugin\Install;
use AC\Plugin\Version;
use ReflectionObject;

class Plugin {

	/**
	 * Location of the plugin main file
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var string
	 */
	private $previous_version_key;

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var Install|null
	 */
	private $installer;

	/**
	 * @var array
	 */
	private $data;

	protected function __construct( $file, $version_key, Version $version = null ) {
		$this->file = (string) $file;
		$this->version_key = (string) $version_key;
		$this->previous_version_key = $this->version_key . '_previous';

		if ( null === $version ) {
			$version = new Version( (string) $this->get_header( 'Version' ) );
		}

		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function get_basename() {
		return plugin_basename( $this->file );
	}

	/**
	 * @return string
	 */
	public function get_dir() {
		return plugin_dir_path( $this->file );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return plugin_dir_url( $this->file );
	}

	public function set_installer( Install $installer ) {
		$this->installer = $installer;
	}

	private function get_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $this->data ) {
			$this->data = get_plugin_data( $this->file, false, false );
		}

		return $this->data;
	}

	/**
	 * @param string $var
	 *
	 * @return string|null
	 */
	private function get_header( $var ) {
		$data = $this->get_data();

		return isset( $data[ $var ] )
			? (string) $data[ $var ]
			: null;
	}

	/**
	 * @return bool
	 */
	public function is_network_active() {
		return is_plugin_active_for_network( $this->get_basename() );
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
		if ( $this->version->is_not_equal( $this->get_stored_version() ) ) {
			return true;
		}

		// Run installer when the current version can not be read from the plugin's header file
		if ( ! $this->version->is_valid() && ! $this->get_stored_version()->is_valid() ) {
			return true;
		}

		return false;
	}

	public function install() {
		if ( ! $this->can_install() ) {
			return;
		}

		if ( $this->installer ) {
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
		return $this->version;
	}

	/**
	 * @param string $version
	 *
	 * @return bool
	 */
	public function is_version_gte( $version ) {
		return $this->version->is_gte( new Version( $version ) );
	}

	/**
	 * @return Version
	 */
	public function get_stored_version() {
		return new Version( (string) get_option( $this->version_key ) );
	}

	/**
	 * @return Version
	 */
	protected function get_previous_stored_version() {
		return new Version( (string) get_option( $this->previous_version_key ) );
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
			$version = $this->version->get_value();
		}

		update_option( $this->previous_version_key, $this->get_stored_version()->get_value(), false );
		update_option( $this->version_key, $version, false );

		return true;
	}

	/**
	 * Check if the plugin was updated or is a new install
	 */
	public function is_new_install() {
		global $wpdb;

		if ( ! $this->get_previous_stored_version()->is_valid() ) {
			return true;
		}

		if ( $this->get_stored_version()->is_valid() ) {
			return false;
		}

		// Before version 3.0.5
		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		return empty( $results );
	}

}