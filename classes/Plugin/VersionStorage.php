<?php

namespace AC\Plugin;

use AC\Storage\KeyValuePair;
use AC\Storage\OptionFactory;

class VersionStorage {

	/**
	 * @var KeyValuePair
	 */
	private $storage;

	public function __construct( $key, $network_activated = false ) {
		$this->storage = ( new OptionFactory() )->create( (string) $key, (bool) $network_activated );
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function save( Version $version ) {
		return $this->storage->save( $version->get_value() );
	}

	/**
	 * @return Version
	 */
	public function get() {
		return new Version( (string) $this->storage->get() );
	}

}