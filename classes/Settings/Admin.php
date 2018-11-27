<?php
namespace AC\Settings;

abstract class Admin {

	/** @var string */
	protected $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string HTML
	 */
	abstract public function render();

}