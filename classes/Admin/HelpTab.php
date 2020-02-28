<?php

namespace AC\Admin;

abstract class HelpTab {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	public function __construct( $title ) {
		$this->id = 'ac-tab-' . sanitize_key( get_called_class() );
		$this->title = $title;
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
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	abstract public function get_content();

}