<?php declare( strict_types=1 );

namespace AC\Asset\InlineScript;

use InvalidArgumentException;

final class Position {

	public const BEFORE = 'before';
	public const AFTER = 'after';

	private $position;

	public function __construct( string $position ) {
		$this->position = $position;

		$this->validate();
	}

	private function validate(): void {
		$valid_positions = [
			self::BEFORE,
			self::AFTER,
		];

		if ( ! in_array( $this->position, $valid_positions, true ) ) {
			throw new InvalidArgumentException( sprintf( 'Position can be %s or %s.', self::BEFORE, self::AFTER ) );
		}
	}

	public static function before(): self {
		return new self( self::BEFORE );
	}

	public static function after(): self {
		return new self( self::AFTER );
	}

	public function __toString(): string {
		return $this->position;
	}

}