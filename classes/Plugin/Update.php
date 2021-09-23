<?php

namespace AC\Plugin;

abstract class Update {

	/**
	 * @var Version
	 */
	protected $version;

	// TODO check all child classes
	public function __construct( Version $version ) {
		$this->version = $version;
	}

	/**
	 * Apply this update
	 * @return void
	 */
	abstract public function apply_update();

	/**
	 * @return Version
	 */
	public function get_version() {
		return $this->version;
	}

}