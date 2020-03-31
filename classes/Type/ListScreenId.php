<?php

namespace AC\Type;

use LogicException;

final class ListScreenId {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @param string $id
	 */
	public function __construct( $id ) {
		if ( ! self::is_valid_id( $id ) ) {
			throw new LogicException( 'Found empty ListScreen identity.' );
		}

		$this->id = $id;
	}

	public static function is_valid_id( $id ) {
		return is_string( $id ) && $id !== '';
	}

	/**
	 * @return self
	 */
	public static function generate() {
		return new self( uniqid() );
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function equals( ListScreenId $id ) {
		return $this->id === $id->get_id();
	}

}