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
	 * @var string
	 */
	private $class;

	public function __construct( $url, $label, $class = '' ) {
		$this->url = (string) $url;
		$this->label = (string) $label;
		$this->class = (string) $class;
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

}