<?php

namespace AC\Asset;

use LogicException;

class InlineScript {

	public function __construct( Script $script, string $data, $position = null ) {
		if ( $position !== null && ! in_array( $position, [ 'before', 'after' ], true ) ) {
			throw new LogicException( 'Invalid script position provided' );
		}

		wp_add_inline_script(
			$script->get_handle(),
			$data,
			$position
		);
	}

	public static function create_before( Script $script, string $data ) {
		return new self( $script, $data, 'before' );
	}

}