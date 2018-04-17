<?php

namespace AC\Plugin;

abstract class Update {

	/**
	 * @var string
	 */
	protected $stored_version;

	/**
	 * @var string Assumes this regex for versions: ^[1-9]\.[0-9]\.[1-9][0-9]?$
	 */
	protected $version;

	public function __construct( $stored_version ) {
		$this->stored_version = $stored_version;
		$this->set_version();
	}

	/**
	 * Check if this update needs to be applied
	 *
	 * @return bool
	 */
	public function needs_update() {
		return $this->is_less_or_equal_stored_version();
	}

	/**
	 * @return bool
	 */
	protected function is_less_or_equal_stored_version() {
		return version_compare( $this->version, $this->stored_version, '>' );
	}

	/**
	 * Apply this update
	 *
	 * @return void
	 */
	public abstract function apply_update();

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set the version this update applies to
	 *
	 * @return void
	 */
	protected abstract function set_version();

}