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
	 * @var Version
	 */
	private $version;

	/**
	 * @var string  $file
	 * @var string  $version_key
	 * @var Version $version
	 */
	protected function __construct( $file, $version_key, Version $version ) {
		$this->file = (string) $file;
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

	// TODO we can just delete this?

	/**
	 * @return void
	 * @deprecated
	 */
	public function install() {
	}

}