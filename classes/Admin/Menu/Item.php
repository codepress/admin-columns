<?php

namespace AC\Admin\Menu;

class Item {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string|null
	 */
	private $class;

	public function __construct( $url, $label, $class = null ) {
		$this->url = $url;
		$this->label = $label;
		$this->class = $class;
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
	 * @return string|null
	 */
	public function get_class() {
		return $this->class;
	}

}