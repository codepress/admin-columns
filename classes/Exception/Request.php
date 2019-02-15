<?php

namespace AC\Exception;

use LogicException;

class Request extends LogicException {

	public static function from_invalid_parameters() {
		return new self( 'Invalid request parameters.' );
	}

}