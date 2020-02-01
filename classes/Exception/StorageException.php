<?php

namespace AC\Exception;

use LogicException;

class StorageException extends LogicException {

	/**
	 * @return self
	 */
	public static function storage_not_writable() {
		return new self( 'Storage is not configured as writable.' );
	}

}