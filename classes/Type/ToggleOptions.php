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

	public static function create_from_array( array $options ): self {
		$first_key = array_key_first( $options );
		$last_key = array_key_last( $options );

		return new self(
			new Option( $first_key, $options[ $first_key ] ),
			new Option( $last_key, $options[ $last_key ] )
		);
	}

}