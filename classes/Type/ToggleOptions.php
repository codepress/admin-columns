<?php

namespace AC\Type;

use AC\Helper\Select\Option;

final class ToggleOptions {

	/**
	 * @var Option
	 */
	private $disabled;

	/**
	 * @var Option
	 */
	private $enabled;

	public function __construct( Option $disabled, Option $enabled ) {
		$this->disabled = $disabled;
		$this->enabled = $enabled;
	}

	/**
	 * @return Option
	 */
	public function get_enabled() {
		return $this->enabled;
	}

	/**
	 * @return Option
	 */
	public function get_disabled() {
		return $this->disabled;
	}

}