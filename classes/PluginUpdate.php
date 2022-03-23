<?php

namespace AC;

use AC\Plugin\Version;

class PluginUpdate {

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var string|null
	 */
	private $package;

	public function __construct( Version $version, $package = null ) {
		$this->version = $version;
		$this->package = $package;
	}

	public function get_version() {
		return $this->version;
	}

	public function has_package() {
		return null !== $this->package;
	}

	public function get_package() {
		return $this->package;
	}

}