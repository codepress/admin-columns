<?php declare( strict_types=1 );

namespace AC\Asset\Script\Inline\Data;

final class Variable {

	private $name;

	private $data;

	public function __construct( string $name, $data ) {
		switch ( gettype( $data ) ) {
			case 'boolean':
			case 'array':
				$data = json_encode( $data );
		}

		$this->name = $name;
		$this->data = (string) $data;
	}

	public function __toString(): string {
		return sprintf( 'var %s = %s;', $this->name, $this->data );
	}

}