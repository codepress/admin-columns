<?php

namespace AC\Admin\Menu;

class Item {

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $url;

	public function __construct( $slug, $label, $url ) {
		$this->slug = $slug;
		$this->label = $label;
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
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
	public function get_url() {
		return $this->url;
	}

}