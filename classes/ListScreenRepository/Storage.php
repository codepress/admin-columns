<?php

declare( strict_types=1 );

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use LogicException;
use WP_User;

final class Storage implements ListScreenRepositoryWritable {

	use ListScreenPermissionTrait;

	/**
	 * @var Storage\ListScreenRepository[]
	 */
	private $repositories = [];

	/**
	 * @return Storage\ListScreenRepository[]
	 */
	public function get_repositories(): array {
		return array_reverse( $this->repositories );
	}

	public function set_repositories( array $repositories ): void {
		foreach ( $repositories as $repository ) {
			if ( ! $repository instanceof ListScreenRepository\Storage\ListScreenRepository ) {
				throw new LogicException( 'Expected a Storage\ListScreenRepository object.' );
			}
		}

		$this->repositories = array_reverse( $repositories );
	}

	public function has_repository( $key ): bool {
		return array_key_exists( $key, $this->repositories );
	}

	public function get_repository( $key ): Storage\ListScreenRepository {
		if ( ! $this->has_repository( $key ) ) {
			throw new LogicException( sprintf( 'Repository with key %s not found.', $key ) );
		}

		return $this->repositories[ $key ];
	}

	public function find_by_user( ListScreenId $id, WP_User $user ): ?ListScreen {
		foreach ( $this->repositories as $repository ) {
			$list_screen = $repository->find_by_user( $id, $user );

			if ( $list_screen ) {
				break;
			}
		}

		return $list_screen ?? null;
	}

	public function find_all_by_assigned_user( string $key, WP_User $user, Sort $sort = null ): ListScreenCollection {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all_by_assigned_user( $key, $user ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		return $sort
			? $sort->sort( $list_screens )
			: $list_screens;
	}

	public function find_all_by_key( string $key, Sort $sort = null ): ListScreenCollection {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all_by_key( $key ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		return $sort
			? $sort->sort( $list_screens )
			: $list_screens;
	}

	public function find_all( Sort $sort = null ): ListScreenCollection {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all() as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		return $sort
			? $sort->sort( $list_screens )
			: $list_screens;
	}

	public function find( ListScreenId $id ): ?ListScreen {
		foreach ( $this->repositories as $repository ) {
			$list_screen = $repository->find( $id );

			if ( $list_screen ) {
				break;
			}
		}

		return $list_screen ?? null;
	}

	public function exists( ListScreenId $id ): bool {
		return null !== $this->find( $id );
	}

	public function save( ListScreen $list_screen ): void {
		$repository = $this->get_writable_repositories( $list_screen );

		if ( empty( $repository ) ) {
			return;
		}

		// Only write in one repository
		$repository[0]->save( $list_screen );
	}

	public function delete( ListScreen $list_screen ): void {
		foreach ( $this->get_writable_repositories( $list_screen ) as $repository ) {
			if ( $repository->find( $list_screen->get_id() ) ) {
				$repository->delete( $list_screen );
				break;
			}
		}
	}

	private function get_writable_repositories( ListScreen $list_screen ): array {
		$repositories = [];

		foreach ( $this->repositories as $repository ) {
			$match = true;

			if ( $repository->has_rules() ) {
				$match = $repository->get_rules()->match( [
					Rule::ID    => $list_screen->has_id() ? $list_screen->get_id() : null,
					Rule::TYPE  => $list_screen->get_key(),
					Rule::GROUP => $list_screen->get_group(),
				] );
			}

			if ( $match && $repository->is_writable() ) {
				$repositories[] = $repository;
			}
		}

		return $repositories;
	}

}