<?php

namespace AC\Storage;

use AC\Expirable;

final class Timestamp
	implements KeyValuePair, Expirable {

	/**
	 * @var KeyValuePair
	 */
	protected $storage;

	public function __construct( KeyValuePair $storage ) {
		$this->storage = $storage;
	}

	/**
	 * @param int|null $time
	 *
	 * @return bool
	 */
	public function is_expired( $time = null ) {
		$value = $this->get();

		if ( false === $value ) {
			return true;
		}

		if ( null === $time ) {
			$time = time();
		}

		return $time > $value;
	}

	/**
	 * @param int $value
	 *
	 * @return bool
	 */
	public function validate( $value ) {
		return preg_match( '/^[1-9][0-9]*$/', $value );
	}

	public function get() {
		return $this->storage->get();
	}

	public function delete() {
		return $this->storage->delete();
	}

	/**
	 * @param int $value
	 *
	 * @return bool
	 */
	public function save( $value ) {
		if ( ! $this->validate( $value ) ) {
			throw new \Exception( 'Value needs to be a positive integer' );
		}

		return $this->storage->save( $value );
	}

}