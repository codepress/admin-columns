<?php

namespace AC\Exception;

use RuntimeException;
use Throwable;

class SourceNotAvailableException extends RuntimeException {

	public function __construct( $code = 0, Throwable $previous = null ) {
		parent::__construct( 'No source available.', $code, $previous );
	}

}