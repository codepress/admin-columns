<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;

class EqualGroup implements Rule {

	/**
	 * @var string
	 */
	private $group;

	/**
	 * @param string $group
	 */
	public function __construct( $group ) {
		$this->group = $group;
	}

	public function match( array $args ) {
		if ( ! isset( $args[ self::GROUP ] ) ) {
			return false;
		}

		return $args[ self::GROUP ] === $this->group;
	}

}