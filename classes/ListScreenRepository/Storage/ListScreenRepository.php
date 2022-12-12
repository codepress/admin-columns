<?php

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\Exception;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Rules;
use AC\ListScreenRepository\SourceAware;
use AC\Type\ListScreenId;
use LogicException;
use WP_User;

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
	public function __construct( AC\ListScreenRepository $repository, bool $writable = null, Rules $rules = null ) {
		if ( null === $writable ) {
			$writable = false;
		}

		$this->repository = $repository;
		$this->writable = $writable && $this->repository instanceof AC\ListScreenRepositoryWritable;
		$this->rules = $rules;
	}

	public function is_writable(): bool {
		return $this->writable;
	}

	public function with_writable( bool $writable ): self {
		return new self(
			$this->repository,
			$writable,
			$this->rules
		);
	}

	public function get_rules(): Rules {
		if ( ! $this->has_rules() ) {
			throw new LogicException( 'No rules defined.' );
		}

		return $this->rules;
	}

	public function has_rules(): bool {
		return $this->rules !== null;
	}

	public function find_using_permissions( ListScreenId $id, WP_User $user ): ?ListScreen {
		$list_screen = $this->repository->find_using_permissions( $id, $user );

		if ( $list_screen && ! $this->is_writable() ) {
			$list_screen->set_read_only( true );
		}

		return $list_screen;
	}

	public function find( ListScreenId $id ): ?ListScreen {
		$list_screen = $this->repository->find( $id );

		if ( $list_screen && ! $this->is_writable() ) {
			$list_screen->set_read_only( true );
		}

		return $list_screen;
	}

	public function exists( ListScreenId $id ): bool {
		return $this->repository->exists( $id );
	}

	public function find_all( array $args = [] ): ListScreenCollection {
		$list_screens = $this->repository->find_all( $args );

		if ( ! $this->is_writable() ) {
			foreach ( $list_screens as $list_screen ) {
				$list_screen->set_read_only( true );
			}
		}

		return $list_screens;
	}

	public function save( ListScreen $list_screen ): void {
		$this->repository->save( $list_screen );
	}

	public function delete( ListScreen $list_screen ): void {
		$this->repository->delete( $list_screen );
	}

	public function get_source( ListScreenId $id ): string {
		if ( ! $this->has_source( $id ) ) {
			throw new Exception\SourceNotAvailableException();
		}

		return $this->repository->get_source( $id );
	}

	public function has_source( ListScreenId $id ): bool {
		return $this->repository instanceof SourceAware && $this->repository->has_source( $id );
	}

}