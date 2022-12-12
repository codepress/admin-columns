<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use InvalidArgumentException;
use LogicException;
use WP_User;

final class Storage implements ListScreenRepositoryWritable {

	use ListScreenPermissionTrait;

	public const ARG_FILTER = 'filter';
	public const ARG_SORT = 'sort';

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

	public function find_using_permissions( ListScreenId $id, WP_User $user ): ?ListScreen {
		return $this->find_all( [ self::ID => $id, self::REQUIRE_USER => $user ] )->get_first();
	}

	public function find_by_key( string $key, Sort $sort = null ): ListScreenCollection {
		return $this->find_all( [
			ListScreenRepository::KEY => $key,
		] );
	}

	// TODO make obsolete...
	public function find_all( array $args = [] ): ListScreenCollection {
		$args = array_merge( [
			self::ARG_FILTER   => [],
			self::ARG_SORT     => null,
			self::REQUIRE_USER => null,
		], $args );

		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all( $args ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		if ( $args[ self::REQUIRE_USER ] instanceof WP_User ) {
			$list_screens = $this->filter_by_permission( $list_screens, $args[ self::REQUIRE_USER ] );
		}

		// TODO can this be removed?
		foreach ( $args[ self::ARG_FILTER ] as $filter ) {
			if ( ! $filter instanceof Filter ) {
				throw new InvalidArgumentException( 'Invalid filter supplied.' );
			}

			$list_screens = $filter->filter( $list_screens );
		}

		// TODO can this be removed?
		if ( $args[ self::ARG_SORT ] instanceof Sort ) {
			$list_screens = $args[ self::ARG_SORT ]->sort( $list_screens );
		}

		return $list_screens;
	}

	private function filter_by_permission( ListScreenCollection $list_screens, WP_User $user ): ListScreenCollection {
		$collection = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->user_can_view_list_screen( $list_screen, $user ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

	public function find( ListScreenId $id ): ?ListScreen {
		return $this->find_all( [ self::ID => $id ] )->get_first() ?: null;
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