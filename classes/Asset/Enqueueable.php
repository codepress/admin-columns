<?php

namespace AC\Asset;

abstract class Enqueueable {

	/**
	 * @var string
	 */
	protected $handle;

	/**
	 * @var Location|null
	 */
	protected $location;

	/**
	 * @var string[]
	 */
	protected $dependencies;

	/**
	 * @param string   $handle
	 * @param Location $location
	 * @param array    $dependencies
	 */
	public function __construct( $handle, Location $location = null, array $dependencies = [] ) {
		$this->handle = (string) $handle;
		$this->location = $location;
		$this->dependencies = $dependencies;
	}

	/**
	 * @return string
	 */
	public function get_handle() {
		return $this->handle;
	}

	/**
	 * @return int|null
	 */
	protected function get_version() {
		$path = $this->location->get_path();

		return file_exists( $path )
			? filemtime( $path )
			: null;
	}

	/**
	 * @return void
	 */

	abstract public function register();

	/**
	 * @return void
	 */

	abstract public function enqueue();

}