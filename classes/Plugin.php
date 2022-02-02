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
	 * @var Version $version
	 */
	protected function __construct( $file, Version $version ) {
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

	/**
	 * @return void
	 * @deprecated
	 */
	public function install() {
	}

}