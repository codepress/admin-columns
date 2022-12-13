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

	public function __construct( string $handle, Location $location = null, array $dependencies = [] ) {
		$this->handle = $handle;
		$this->location = $location;
		$this->dependencies = $dependencies;
	}

	public function get_handle(): string {
		return $this->handle;
	}

	protected function get_version(): ?int {
		$path = $this->location->get_path();

		return file_exists( $path )
			? filemtime( $path )
			: null;
	}

	abstract public function register(): void;

	abstract public function enqueue(): void;

}