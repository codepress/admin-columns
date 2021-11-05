<?php

namespace AC\Plugin;

use AC\Storage\KeyValueFactory;
use AC\Storage\KeyValuePair;

class VersionStorage {

	const SUFFIX_PREVIOUS = '_previous';

	/**
	 * @var KeyValuePair
	 */
	private $key;

	/**
	 * @var KeyValuePair
	 */
	private $previous_key;

	public function __construct( $key, KeyValueFactory $option_factory ) {
		$this->key = $option_factory->create( (string) $key );
		$this->previous_key = $option_factory->create( $key . self::SUFFIX_PREVIOUS );
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function save( Version $version ) {
		$this->previous_key->save( $this->get()->get_value() );
		$this->key->save( $version->get_value() );

		return true;
	}

	/**
	 * @return Version
	 */
	public function get() {
		return new Version( (string) $this->key->get() );
	}

	/**
	 * @return Version
	 */
	public function get_previous() {
		return new Version( (string) $this->previous_key->get() );
	}

}