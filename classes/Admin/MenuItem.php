<?php
namespace AC\Admin;

class MenuItem {

	/** @var string */
	private $slug;

	/** @var string */
	private $label;

	/** @var string */
	private $url;

	/** @var bool */
	private $is_active;

	public function __construct( $slug, $label, $url, $is_active = false ) {
		$this->slug = $slug;
		$this->label = $label;
		$this->url = $url;
		$this->is_active = (bool) $is_active;
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
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		return $this->is_active;
	}

}