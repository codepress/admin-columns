<?php declare( strict_types=1 );

namespace AC\Asset;

class ScriptDataFactory {

	public function create_variable( string $variable_name, $data ): string {
		$type = gettype( $data );

		switch ( $type ) {
			case 'array':
				$data = json_encode( $data );

				break;
			case 'boolean':
				$data = $data ? 'true' : 'false';

				break;
		}

		return sprintf( "var %s = %s;", $variable_name, $data );
	}

}