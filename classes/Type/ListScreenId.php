<?php declare( strict_types=1 );

namespace AC\Type;

use LogicException;

final class ListScreenId {

	private string $id;

	public function __construct( string $id ) {
		if ( ! self::is_valid_id( $id ) ) {
			throw new LogicException( 'Found empty ListScreen identity.' );
		}

		$this->id = $id;
	}

	public static function is_valid_id( $id ): bool {
		return is_string( $id ) && '' !== $id;
	}

	public static function generate(): self {
		return new self( uniqid() );
	}

	public function get_id(): string {
		return $this->id;
	}

	public function equals( ListScreenId $id ): bool {
		return $this->id === $id->get_id();
	}

	public function __toString(): string {
		return $this->id;
	}

}