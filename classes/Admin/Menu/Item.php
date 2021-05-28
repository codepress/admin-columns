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

	public function __construct( $slug, $label, $url = null ) {
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
	 * @param string $url
	 */
	// TODO remove
	public function set_url( $url ) {
		$this->url = $url;
	}

	public function has_url() {
		return null !== $this->url;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}