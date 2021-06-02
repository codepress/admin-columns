<?php

namespace AC\Admin;

use AC\Renderable;

abstract class Page implements Renderable {

	/**
	 * @var string
	 */
	private $slug;

	public function __construct( $slug ) {
		$this->slug = (string) $slug;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

}