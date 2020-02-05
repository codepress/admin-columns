<?php

namespace AC\ListScreenRepository\Storage;

use Iterator;

final class OrderedList implements Iterator {

	/**
	 * @var ListScreenRepository[]
	 */
	private $repositories;

	/**
	 * @param ListScreenRepository $repository
	 * @param string|null          $key
	 */
	public function insert_before( ListScreenRepository $repository, $key = null ) {
		if ( null === $key ) {
			$key = $this->current()->get_key();
		}

		foreach( $this->repositories as $k => $v ) {
			if ( $key === $v->get_key() ) {
				array_splice( $this->repositories, $k, 0, $repository );

				return;
			}
		}

		// TODO throw exception?
	}

	/**
	 * @param ListScreenRepository $repository
	 * @param string|null          $key
	 */
	public function insert_after( ListScreenRepository $repository, $key = null ) {
		if ( null === $key ) {
			$key = end( $this->repositories )->get_key();
		}

		foreach( $this->repositories as $index => // TODO ) {
			if ( $key === $v->get_key() ) {
				array_splice( $this->repositories, $k, 0, $new_repository );

				return;
			}
		}

	}

	/**
	 * @return ListScreenRepository
	 */
	public function current() {
		return current( $this->repositories );
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		return next( $this->repositories );
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return key( $this->repositories );
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
		return reset( $this->repositories );
	}
}