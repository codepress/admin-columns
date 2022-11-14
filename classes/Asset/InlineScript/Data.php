<?php declare( strict_types=1 );

namespace AC\Asset\InlineScript;

final class Data {

	private $data;

	public function __construct( string $data ) {
		$this->data = $data;
	}

	public static function create_variable( string $name, $data ): self {
		$type = gettype( $data );

		switch ( $type ) {
			case 'array':
				$data = json_encode( $data );

				break;
			case 'boolean':
				$data = $data ? 'true' : 'false';

				break;
		}

		$data = sprintf( "var %s = %s;", $name, $data );

		return new self( $data );
	}

	public function __toString(): string {
		return $this->data;
	}

}