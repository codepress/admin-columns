<?php

namespace AC;

use Iterator;

/**
 * Used to hold values from the same type
 */
class Collection
	implements Iterator {

	/**
	 * @var array
	 */
	protected $items;

	public function __construct( array $items = [] ) {
		$this->items = $items;
	}

	public function all() {
		return $this->items;
	}

	public function has( $key ) {
		return isset( $this->items[ $key ] );
	}

	public function put( $key, $value ) {
		$this->items[ $key ] = $value;

		return $this;
	}

	public function push( $value ) {
		$this->items[] = $value;
	}

	public function get( $key, $default = null ) {
		if ( $this->has( $key ) ) {
			return $this->items[ $key ];
		}

		return $default;
	}

	public function __get( $key ) {
		return $this->get( $key );
	}

	#[\ReturnTypeWillChange]
	public function rewind() {
		reset( $this->items );
	}

	public function first() {
		return reset( $this->items );
	}

	#[\ReturnTypeWillChange]
	public function current() {
		return current( $this->items );
	}

	#[\ReturnTypeWillChange]
	public function key() {
		return key( $this->items );
	}

	#[\ReturnTypeWillChange]
	public function next() {
		return next( $this->items );
	}

	public function get_copy() {
		return $this->items;
	}

	#[\ReturnTypeWillChange]
	public function valid() {
		$key = $this->key();

		return ( $key !== null && $key !== false );
	}

	public function count() {
		return count( $this->items );
	}

	/**
	 * Filter collection items
	 * @return Collection
	 */
	public function filter() {
		return new Collection( ac_helper()->array->filter( $this->items ) );
	}

	/**
	 * Limit array to max number of items
	 *
	 * @param int $length
	 *
	 * @return int Number of removed items
	 */
	public function limit( $length ) {
		$count = $this->count();

		if ( 0 < $length ) {
			$this->items = array_slice( $this->items, 0, $length );
		}

		return $count - $this->count();
	}

	/**
	 * @param string $glue
	 *
	 * @return string
	 */
	public function implode( $glue = '' ) {
		return implode( $glue, $this->items );
	}

}