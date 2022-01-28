<?php

namespace AC;

use AC\Asset\Location;
use AC\Plugin\Version;

class Plugin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	protected $version_key;

	/**
	 * @var Version
	 */
	private $version;

	// TODO test if not null of $version works
	protected function __construct( $file, $version_key, Version $version ) {
		$this->file = (string) $file;
		$this->version_key = (string) $version_key;
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	// TODO David remove? Is delegated to setup class?
	public function get_version_key() {
		return $this->version_key;
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
		return ( new PluginInformation( $this->get_basename() ) )->is_network_active();
	}

	/**
	 * @return Version
	 */
	public function get_version() {
		return $this->version;
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
	 * For backwards compatbility with the `Depedencies` class
	 *
	 * @param string
	 *
	 * @return bool
	 */
	public function is_version_gte( $version ) {
		return $this->version->is_gte( new Version( (string) $version ) );
	}

	/**
	 * @return void
	 * @deprecated
	 */
	public function install() {
	}

}