<?php

namespace AC\Asset;

class Script implements Enqueueable {

	/**
	 * @var string
	 */
	private $handle;

	/**
	 * @var Location
	 */
	private $location;

	/**
	 * @var string[]
	 */
	private $dependencies;

	/**
	 * @param          $handle
	 * @param Location $location
	 * @param array    $dependencies
	 */
	public function __construct( $handle, Location $location, array $dependencies = [] ) {
		$this->handle = $handle;
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
	 * @return Location
	 */
	private function get_location() {
		return $this->location;
	}

	/**
	 * @return string[]
	 */
	private function get_dependencies() {
		return $this->dependencies;
	}

	public function register() {
		$path = $this->get_location()->get_path();
		$version = file_exists( $path ) ? filemtime( $path ) : null;

		wp_register_script(
			$this->get_handle(),
			$this->get_location()->get_url(),
			$this->get_dependencies(),
			$version
		);
	}

	public function enqueue() {
		if ( wp_script_is( $this->get_handle() ) ) {
			return;
		}

		if ( ! wp_script_is( $this->get_handle(), 'registered' ) ) {
			$this->register();
		}

		wp_enqueue_script( $this->get_handle() );
	}

}
