<?php

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\ListScreen;
use AC\ListScreenRepository\Rules;
use LogicException;

class ListScreenRepository implements AC\ListScreenRepository {

	/**
	 * @var ListScreenRepository
	 */
	private $repository;

	/**
	 * @var bool
	 */
	private $writable;

	/**
	 * @var Rules
	 */
	private $rules;

	/**
	 * @param AC\ListScreenRepository $repository
	 * @param bool|null               $writable
	 * @param Rules|null              $rules
	 */
	public function __construct( AC\ListScreenRepository $repository, $writable = null, Rules $rules = null ) {
		if ( null === $writable ) {
			$writable = false;
		}

		$this->repository = $repository;
		$this->writable = $this->set_writable( $writable );
		$this->rules = $rules;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return get_class( $this->repository );
	}

	/**
	 * @return bool
	 */
	public function is_writable() {
		return $this->writable;
	}

	/**
	 * @param bool $writable
	 */
	public function set_writable( $writable ) {
		if ( ! is_bool( $writable ) ) {
			throw new LogicException( 'Expected boolean value.' );
		}

		$this->writable = $writable;
	}

	/**
	 * @return Rules
	 */
	public function get_rules() {
		if ( ! $this->has_rules() ) {
			throw new LogicException( 'No rules defined.' );
		}

		return $this->rules;
	}

	/**
	 * @return bool
	 */
	public function has_rules() {
		return $this->rules !== null;
	}

	/**
	 * @inheritDoc
	 */
	public function find( $id ) {
		return $this->repository->find( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function exists( $id ) {
		return $this->repository->exists( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function find_all( array $args = [] ) {
		return $this->repository->find_all( $args );
	}

	/**
	 * @inheritDoc
	 */
	public function save( ListScreen $list_screen ) {
		$this->repository->save( $list_screen );
	}

	/**
	 * @inheritDoc
	 */
	public function delete( ListScreen $list_screen ) {
		$this->repository->delete( $list_screen );
	}

}