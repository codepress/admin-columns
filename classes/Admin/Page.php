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
	abstract public function display();

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
	 * @return void
	 */
//	public function register() {
//		// Run hooks
//	}

	/**
	 * @return void
	 */
	public function register_ajax() {
		// Run ajax hooks
	}

}