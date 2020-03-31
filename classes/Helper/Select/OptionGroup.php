<?php

namespace AC\Helper\Select;

final class OptionGroup {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var Option[]
	 */
	private $options;

	/**
	 * @param string   $label
	 * @param Option[] $options
	 */
	public function __construct( $label, array $options = [] ) {
		$this->label = $label;

		foreach ( $options as $option ) {
			$this->add_option( $option );
		}
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return Option[]
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @param Option $option
	 *
	 * @return $this
	 */
	protected function add_option( Option $option ) {
		$this->options[] = $option;

		return $this;
	}

}