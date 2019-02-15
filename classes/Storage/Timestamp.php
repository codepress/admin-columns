<?php

namespace AC\Storage;

use AC\Expirable;
use Exception;
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

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get( array $args = array() ) {
		return $this->storage->get( $args );
	}

	public function delete() {
		return $this->storage->delete();
	}

	/**
	 * @param int $value
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function save( $value ) {
		if ( ! $this->validate( $value ) ) {
			throw new LogicException( 'Value needs to be a positive integer.' );
		}

		return $this->storage->save( $value );
	}

}