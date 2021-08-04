<?php

namespace AC;

use AC\Asset\Location;
use AC\Plugin\Version;
use ReflectionObject;
use WP_Roles;

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
	 * @var Installer|null
	 */
	private $installer;

	/**
	 * @var array
	 */
	private $data;

	protected function __construct( $file, $version_key ) {
		$this->file = (string) $file;
		$this->version_key = (string) $version_key;
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

	public function set_installer( Installer $installer ) {
		$this->installer = $installer;
	}

	private function get_data() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $this->data ) {
			$this->data = get_plugin_data( $this->file, false, false );
		}

		return $this->data;
	}

	private function get_header( $var ) {
		$data = $this->get_data();

		return isset( $data[ $var ] )
			? $data[ $var ]
			: null;
	}

	/**
	 * Check if plugin is network activated
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
		if ( $this->get_version()->is_not_equal( $this->get_stored_version() ) ) {
			return true;
		}

		// Run installer when the current version can not be read from the plugin's header file
		if ( ! $this->get_version()->is_valid() && ! $this->get_stored_version()->is_valid() ) {
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
		return new Version( (string) get_option( $this->version_key ) );
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

		return update_option( $this->version_key, $version, false );
	}

	/**
	 * Check if the plugin was updated or is a new install
	 */
	public function is_new_install() {
		global $wpdb;

		if ( $this->get_stored_version()->is_valid() ) {
			return false;
		}

		// Before version 3.0.5
		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		return empty( $results );
	}

}