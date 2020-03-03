<?php

namespace AC\Storage;

use AC\Expirable;
use LogicException;

final class Timestamp implements KeyValuePair, Expirable {

	/**
	 * @var KeyValuePair
	 */
	private $storage;

	/**
	 * @param KeyValuePair $storage
	 */
	public function __construct( KeyValuePair $storage ) {
		$this->storage = $storage;
	}

	/**
	 * @param int|null $time
	 *
	 * @return bool
	 */
	public function is_expired( $time = null ) {
		if ( null === $time ) {
			$time = time();
		}

		return $time > (int) $this->get();
	}

	/**
	 * @param int $value
	 *
	 * @return bool
	 */
	public function validate( $value ) {
		return preg_match( '/^[1-9]\d*$/', $value );
	}

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get( array $args = [] ) {
		return $this->storage->get( $args );
	}

	public function delete() {
		return $this->storage->delete();
	}

	/**
	 * @param int $value
	 *
	 * @return bool
	 * @throws LogicException
	 */
	public function save( $value ) {
		if ( ! $this->validate( $value ) ) {
			throw new LogicException( 'Value needs to be a positive integer.' );
		}

		return $this->storage->save( $value );
	}

}