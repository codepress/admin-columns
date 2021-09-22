<?php

namespace AC\Plugin;

class StoredVersions {

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $previous_key;

	public function __construct( $key, $previous_key ) {
		$this->key = (string) $key;
		$this->previous_key = (string) $previous_key;
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function update_version( Version $version ) {
		update_option( $this->previous_key, $this->get_version()->get_value(), false );
		update_option( $this->key, $version, false );

		return true;
	}

	/**
	 * @return Version
	 */
	public function get_version() {
		return new Version( (string) get_option( $this->key ) );
	}

	/**
	 * @return Version
	 */
	public function get_previous_version() {
		return new Version( (string) get_option( $this->previous_key ) );
	}

}