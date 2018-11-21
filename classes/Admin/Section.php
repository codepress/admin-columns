<?php
namespace AC\Admin;

abstract class Section {

	/** @var string */
	private $id;

	/** @var string */
	private $title;

	/** @var string */
	private $description;

	public function __construct( $id, $title, $description ) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	public function register() {
		// Run hooks
	}

	abstract public function render();

}