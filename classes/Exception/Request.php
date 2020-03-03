<?php

namespace AC\Exception;

use RuntimeException;

class Request extends RuntimeException {

	public static function parameters_invalid() {
		return new self( 'Invalid request parameters.' );
	}

}