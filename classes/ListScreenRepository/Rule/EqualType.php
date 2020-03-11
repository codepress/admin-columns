<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;

class EqualType implements Rule {

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @param string $type
	 */
	public function __construct( $type ) {
		$this->type = $type;
	}

	/**
	 * @inheritDoc
	 */
	public function match( array $args ) {
		if ( ! isset( $args[ self::TYPE ] ) ) {
			return false;
		}

		return $args[ self::TYPE ] === $this->type;
	}

}