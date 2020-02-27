<?php

namespace AC\Asset;

class Style implements Enqueueable {

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
	public function __construct( $handle, Location $location, array $dependencies = array() ) {
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
	 * @return array|string[]
	 */
	private function get_dependencies() {
		return $this->dependencies;
	}

	public function register() {
		wp_register_style(
			$this->get_handle(),
			$this->get_location()->get_url(),
			$this->get_dependencies(),
			filemtime( $this->get_location()->get_path() )
		);
	}

	public function enqueue() {
		if ( wp_style_is( $this->get_handle() ) ) {
			return;
		}

		if ( ! wp_style_is( $this->get_handle(), 'registered' ) ) {
			$this->register();
		}

		wp_enqueue_style( $this->get_handle() );
	}

}
