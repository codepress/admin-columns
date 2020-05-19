<?php

namespace AC\Exception;

use RuntimeException;

class SourceNotAvailableException extends RuntimeException {

	public function __construct( $code = 0 ) {
		parent::__construct( 'No source available.', $code );
	}

}