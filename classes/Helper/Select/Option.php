<?php

namespace AC\Helper\Select;

class Option {

	/**
	 * @var string
	 */
	private $value;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @param string $value
	 * @param string $label
	 */
	public function __construct( $value, $label ) {
		$this->value = $value;
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

}