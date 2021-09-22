<?php

namespace AC\Plugin;

class StoredVersion {

	const SUFFIX_PREVIOUS = '_previous';

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $previous_key;

	public function __construct( $key ) {
		$this->key = (string) $key;
		$this->previous_key = $this->key . self::SUFFIX_PREVIOUS;
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function save( Version $version ) {
		update_option( $this->previous_key, $this->get()->get_value(), false );
		update_option( $this->key, $version->get_value(), false );

		return true;
	}

	/**
	 * @return Version
	 */
	public function get() {
		return new Version( (string) get_option( $this->key ) );
	}

	/**
	 * @return Version
	 */
	public function get_previous() {
		return new Version( (string) get_option( $this->previous_key ) );
	}

}