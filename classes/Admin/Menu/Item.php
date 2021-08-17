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
	private $url;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $class;

	/**
	 * @var string
	 */
	private $target;

	public function __construct( $slug, $url, $label, $class = '', $target = '' ) {
		$this->slug = (string) $slug;
		$this->url = (string) $url;
		$this->label = (string) $label;
		$this->class = (string) $class;
		$this->target = (string) $target;
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

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_class() {
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function get_target() {
		return $this->target;
	}

}