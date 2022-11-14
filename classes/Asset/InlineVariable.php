<?php

namespace AC\Asset;

class InlineVariable extends InlineScript {

	/**
	 * @param Script $script
	 * @param string $name
	 * @param mixed  $data
	 */
	public function __construct( Script $script, string $name, $data ) {
		if ( is_array( $data ) ) {
			$data = json_encode( $data );
		}

		if ( is_bool( $data ) ) {
			$data = $data ? 'true' : 'false';
		}

		$script_data = sprintf( "var %s = %s;", $name, $data );

		parent::__construct( $script, $script_data, 'before' );
	}

}