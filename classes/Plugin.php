<?php

namespace AC;

use AC\Asset\Location;
use AC\Plugin\Install;
use AC\Plugin\PluginHeader;
use AC\Plugin\Setup;
use AC\Plugin\StoredVersion;
use AC\Plugin\Version;

class Plugin {

	/**
	 * Location of the plugin main file
	 * @var string
	 */
	private $file;

	/**
	 * @var StoredVersion
	 */
	private $stored_version;

	/**
	 * @var Version
	 */
	private $version;

	protected function __construct( $file, $version_key, Version $version = null ) {
		if ( null === $version ) {
			$version = ( new PluginHeader( $file ) )->get_version();
		}

		$this->file = (string) $file;
		$this->stored_version = new StoredVersion( (string) $version_key );
		$this->version = $version;
	}

	/**
	 * @return StoredVersion
	 */
	public function get_stored_version() {
		return $this->stored_version;
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

	/**
	 * @return bool
	 */
	public function is_network_active() {
		return is_plugin_active_for_network( $this->get_basename() );
	}

	// TODO remove
	public function install() {
//		$setup = new Setup( $this->version, $this->stored_version, null, $this->installer );
//		$setup->run();
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
	 * @return bool
	 */
	public function is_new_install() {
		return ! $this->stored_version->get_previous()->is_valid();
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

}