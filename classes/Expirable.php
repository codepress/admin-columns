<?php

namespace AC;

interface Expirable {

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_expired( $value = null );

}