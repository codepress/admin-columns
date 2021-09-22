<?php

namespace AC\Plugin;

abstract class Update {

	/**
	 * @var Version
	 */
	protected $stored_version;

	/**
	 * @var Version
	 */
	protected $version;

	public function __construct( Version $stored_version, Version $version ) {
		$this->stored_version = $stored_version;
		$this->version = $version;
	}

	/**
	 * Check if this update needs to be applied
	 * @return bool
	 */
	public function needs_update() {
		return $this->version->is_gt( $this->stored_version );
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