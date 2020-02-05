<?php

namespace AC\ListScreenRepository\Storage;

use Iterator;

final class Collection implements Iterator {

	/**
	 * @var ListScreenRepository[]
	 */
	private $data;

	public function add( ListScreenRepository $list_screen_repository ) {
		$this->data[] = $list_screen_repository;
	}

	/**
	 * @return ListScreenRepository
	 */
	public function current() {
		return current( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		return next( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return key( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function valid() {
		return null !== $this->key();
	}

	/**
	 * @inheritDoc
	 */
	public function rewind() {
		return reset( $this->data );
	}
}