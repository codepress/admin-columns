<?php

namespace AC\Asset\Location;

use AC\Asset\Location;

final class Absolute implements Location {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @param string $url
	 * @param string $path
	 */
	public function __construct( $url, $path ) {
		$this->url = (string) $url;
		$this->path = (string) $path;
	}

	/**
	 * @param string $suffix
	 *
	 * @return self
	 */
	public function with_suffix( $suffix ) {
		$url = $this->get_url() . $suffix;
		$path = $this->get_path() . $suffix;

		return new self( $url, $path );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

}