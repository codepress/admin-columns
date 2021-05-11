<?php

namespace AC\Type;

use AC\Helper\Select\Option;

final class ToggleValues {

	/**
	 * @var Option
	 */
	private $disabled_option;

	/**
	 * @var Option
	 */
	private $enabled_option;

	public function __construct( Option $disabled_value, Option $enabled_option ) {
		$this->disabled_option = $disabled_value;
		$this->enabled_option = $enabled_option;
	}

	/**
	 * @return Option
	 */
	public function get_enabled_option() {
		return $this->enabled_option;
	}

	/**
	 * @return Option
	 */
	public function get_disabled_option() {
		return $this->disabled_option;
	}

}