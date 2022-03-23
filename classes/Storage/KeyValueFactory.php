<?php

namespace AC\Storage;

interface KeyValueFactory {

	/**
	 * @param string $key
	 *
	 * @return KeyValuePair
	 */
	public function create( $key );

}