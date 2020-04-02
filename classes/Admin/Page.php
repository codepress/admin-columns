<?php

namespace AC\Admin;

use AC\Renderable;

abstract class Page implements Renderable {

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $title;

	public function __construct( $slug, $title ) {
		$this->slug = (string) $slug;
		$this->title = (string) $title;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

}