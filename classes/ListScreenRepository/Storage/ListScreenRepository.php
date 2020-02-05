<?php

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\Exception;
use AC\ListScreen;
use AC\ListScreenRepository\Rules;
use AC\ListScreenRepository\SourceAware;
use LogicException;

class ListScreenRepository implements AC\ListScreenRepository, SourceAware {

	/**
	 * @var string
	 */
	private $key;

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
	 * @param string key
	 * @param AC\ListScreenRepository $repository
	 * @param bool|null               $writable
	 * @param Rules|null              $rules
	 */
	public function __construct( $key, AC\ListScreenRepository $repository, $writable = null, Rules $rules = null ) {
		if ( ! is_string( $key ) || 2 > strlen( $key ) ) {
			throw new LogicException( 'Expected a string of minimal 2 characters as key.' );
		}

		if ( null === $writable ) {
			$writable = false;
		}

		$this->repository = $repository;
		$this->writable = $this->set_writable( $writable );
		$this->rules = $rules;
		$this->key = $key;
		$this->writable = $writable;
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return $this->key;
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

	/**
	 * @inheritDoc
	 */
	public function get_source( $id ) {
		if ( ! $this->has_source( $id ) ) {
			throw new Exception\SourceNotAvailable();
		}

		return $this->repository->get_source( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function has_source( $id ) {
		return $this->repository instanceof SourceAware && $this->repository->has_source( $id );
	}
}