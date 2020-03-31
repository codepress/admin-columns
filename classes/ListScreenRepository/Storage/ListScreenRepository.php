<?php

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\Exception;
use AC\ListScreen;
use AC\ListScreenRepository\Rules;
use AC\ListScreenRepository\SourceAware;
use AC\Type\ListScreenId;
use LogicException;

class ListScreenRepository implements AC\ListScreenRepositoryWritable, SourceAware {

	/**
	 * @var AC\ListScreenRepository
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
		$this->writable = $writable && $this->repository instanceof AC\ListScreenRepositoryWritable;
		$this->rules = $rules;
	}

	/**
	 * @return bool
	 */
	public function is_writable() {
		return $this->writable;
	}

	/**
	 * @param bool $writable
	 *
	 * @return self
	 */
	public function with_writable( $writable ) {
		return new self(
			$this->repository,
			$writable,
			$this->rules
		);
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
	public function find( ListScreenId $id ) {
		$list_screen = $this->repository->find( $id );

		if ( $list_screen && ! $this->is_writable() ) {
			$list_screen->set_read_only( true );
		}

		return $list_screen;
	}

	/**
	 * @inheritDoc
	 */
	public function exists( ListScreenId $id ) {
		return $this->repository->exists( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function find_all( array $args = [] ) {
		$list_screens = $this->repository->find_all( $args );

		if ( ! $this->is_writable() ) {
			foreach ( $list_screens as $list_screen ) {
				$list_screen->set_read_only( true );
			}
		}

		return $list_screens;
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
	public function get_source( ListScreenId $id ) {
		if ( ! $this->has_source( $id ) ) {
			throw new Exception\SourceNotAvailableException();
		}

		return $this->repository->get_source( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function has_source( ListScreenId $id ) {
		return $this->repository instanceof SourceAware && $this->repository->has_source( $id );
	}

}