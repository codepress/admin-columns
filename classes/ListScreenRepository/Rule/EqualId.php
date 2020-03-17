<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;
use AC\Type\ListScreenId;

class EqualId implements Rule {

	/**
	 * @var ListScreenId
	 */
	private $id;

	public function __construct( ListScreenId $id ) {
		$this->id = $id;
	}

	/**
	 * @inheritDoc
	 */
	public function match( array $args ) {
		if ( ! isset( $args[ self::ID ] ) ) {
			return false;
		}

		return $this->id->equals( $args[ self::ID ] );
	}

}