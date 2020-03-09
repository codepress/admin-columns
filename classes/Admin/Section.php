<?php

namespace AC\Admin;

abstract class Section implements Renderable {

	/**
	 * @var string
	 */
	protected $slug;

	public function __construct( $slug ) {
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

}