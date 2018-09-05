<?php

namespace AC;

abstract class ArrayIterator
	implements \Iterator {

	/**
	 * @var array
	 */
	protected $array;

	/**
	 * @param array $collection
	 */
	public function __construct( array $array ) {
		$this->array = $array;
	}

	/**
	 * @inheritDoc
	 */
	public function current() {
		return current( $this->array );
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		return next( $this->array );
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return key( $this->array );
	}

	/**
	 * @inheritDoc
	 */
	public function valid() {
		return $this->key() !== null;
	}

	/**
	 * @inheritDoc
	 */
	public function rewind() {
		return reset( $this->array );
	}

	/**
	 * @param $key
	 *
	 * @return false|mixed
	 */
	public function get_offset( $offset ) {
		if ( ! $this->has_offset( $offset ) ) {
			return false;
		}

		return $this->array[ $offset ];
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function has_offset( $offset ) {
		return array_key_exists( $offset, $this->array );
	}

	/**
	 * @return array
	 */
	public function get_copy() {
		$copy = $this->array;

		reset( $copy );

		return $copy;
	}

}