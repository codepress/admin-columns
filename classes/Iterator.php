<?php

namespace AC;

abstract class Iterator implements \Iterator {

	protected $data = [];

	public function current() {
		return current( $this->data );
	}

	public function next() {
		return next( $this->data );
	}

	public function key() {
		return key( $this->data );
	}

	public function valid() {
		return $this->key() !== null;
	}

	public function rewind() {
		reset( $this->data );
	}

}