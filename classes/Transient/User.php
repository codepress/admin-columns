<?php

namespace AC\Transient;

use AC\Storage;
use AC\Transient;

class User extends Transient {

	/**
	 * @param $key
	 *
	 * @throws \Exception
	 */
	public function __construct( $key ) {
		parent::__construct( $key );

		$this->option = new Storage\UserMeta( $key );
		$this->timestamp = new Storage\Timestamp(
			new Storage\UserMeta( $key . '_timestamp' )
		);
	}
}