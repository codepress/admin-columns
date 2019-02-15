<?php

namespace AC;

use Iterator;

class ArrayIterator
	implements Iterator {

	/**
	 * @var array
	 */
	protected $array;

	/**
	 * @param array $array
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
	 * @param string $offset
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
	 * @param string $offset
	 *
	 * @return bool
	 */
	public function has_offset( $offset ) {
		return array_key_exists( $offset, $this->array );
	}

	/**
	 * @param $value
	 *
	 * @return false|int|string
	 */
	public function search( $value ) {
		return array_search( $value, $this->array );
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->array );
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