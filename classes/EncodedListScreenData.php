<?php

namespace AC;

use Iterator;

final class EncodedListScreenData implements Iterator {

	/**
	 * @var array
	 */
	private $data = [];

	public function add( array $data ) {
		$this->data[] = $data;
	}

	#[ReturnTypeWillChange]
	public function rewind() {
		reset( $this->data );
	}

	/**
	 * @return array
	 */
	#[ReturnTypeWillChange]
	public function current() {
		return current( $this->data );
	}

	#[ReturnTypeWillChange]
	public function key() {
		return key( $this->data );
	}

	#[ReturnTypeWillChange]
	public function next() {
		return next( $this->data );
	}

	#[ReturnTypeWillChange]
	public function valid() {
		$key = $this->key();

		return ( $key !== null && $key !== false );
	}

}