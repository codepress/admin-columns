<?php
namespace AC\Admin;

abstract class Page {

	/** @var string */
	private $slug;

	/** @var string */
	private $label;

	public function __construct( $slug, $label ) {
		$this->slug = $slug;
		$this->label = $label;
	}

	/**
	 * @return void
	 */
	abstract public function render();

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
	 * @return bool
	 */
	public function show_in_menu() {
		return true;
	}

}