<?php

namespace AC\Plugin;

use AC\Storage\KeyValueFactory;
use AC\Storage\KeyValuePair;

class VersionStorage {

	/**
	 * @var KeyValuePair
	 */
	private $key;

	public function __construct( $key, KeyValueFactory $option_factory ) {
		$this->key = $option_factory->create( (string) $key );
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function save( Version $version ) {
		$this->key->save( $version->get_value() );

		return true;
	}

	/**
	 * @return Version
	 */
	public function get() {
		return new Version( (string) $this->key->get() );
	}

}