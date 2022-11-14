<?php declare( strict_types=1 );

namespace AC\Asset\Script\Inline;

final class Variable {

	private $name;

	private $data;

	public function __construct( string $name, $data ) {
		$type = gettype( $data );

		switch ( $type ) {
			case 'array':
				$data = json_encode( $data );

				break;
			case 'boolean':
				$data = $data ? 'true' : 'false';

				break;
		}

		$this->name = $name;
		$this->data = (string) $data;
	}

	public function __toString(): string {
		return sprintf( "var %s = %s;", $this->name, $this->data );
	}

}