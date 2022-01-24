<?php

namespace AC\Plugin;

abstract class Update {

	/**
	 * @var Version
	 */
	protected $version;

	public function __construct( Version $version ) {
		$this->version = $version;
	}

	/**
	 * @param Version $current_version
	 *
	 * @return bool
	 */
	public function needs_update( Version $current_version ) {
		return $this->version->is_gt( $current_version );
	}

	/**
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