<?php

namespace AC;

use Iterator;

class ArrayIterator implements Iterator {

	/**
	 * @var array
	 */
	protected $array;

	public function __construct( array $array = [] ) {
		$this->array = $array;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		return current( $this->array );
	}

	#[\ReturnTypeWillChange]
	public function next() {
		return next( $this->array );
	}

	#[\ReturnTypeWillChange]
	public function key() {
		return key( $this->array );
	}

	#[\ReturnTypeWillChange]
	public function valid() {
		return $this->key() !== null;
	}

	#[\ReturnTypeWillChange]
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
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function search( $value ) {
		return false !== array_search( $value, $this->array, true );
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