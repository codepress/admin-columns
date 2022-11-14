<?php

namespace AC\Asset;

final class InlineScriptFactory {

	public function create_variable( Script $script, string $name, $data, string $position = null ): InlineScript {
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

		return new InlineScript(
			$script,
			$data,
			$position
		);
	}

}