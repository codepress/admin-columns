<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use InvalidArgumentException;
use LogicException;

final class Storage implements ListScreenRepositoryWritable {

	const ARG_FILTER = 'filter';
	const ARG_SORT = 'sort';

	/**
	 * @var Storage\ListScreenRepository[]
	 */
	private $repositories = [];

	/**
	 * @return Storage\ListScreenRepository[]
	 */
	public function get_repositories() {
		return array_reverse( $this->repositories );
	}

	public function set_repositories( array $repositories ) {
		foreach ( $repositories as $repository ) {
			if ( ! $repository instanceof ListScreenRepository\Storage\ListScreenRepository ) {
				throw new LogicException( 'Expected a Storage\ListScreenRepository object.' );
			}
		}

		$this->repositories = array_reverse( $repositories );
	}

	public function has_repository( $key ) {
		return array_key_exists( $key, $this->repositories );
	}

	public function get_repository( $key ) {
		if ( ! $this->has_repository( $key ) ) {
			throw new LogicException( sprintf( 'Repository with key %s not found.', $key ) );
		}

		return $this->repositories[ $key ];
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$args = array_merge( [
			self::ARG_FILTER => [],
			self::ARG_SORT   => null,
		], $args );

		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all( $args ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		foreach ( $args[ self::ARG_FILTER ] as $filter ) {
			if ( ! $filter instanceof Filter ) {
				throw new InvalidArgumentException( 'Invalid filter supplied.' );
			}

			$list_screens = $filter->filter( $list_screens );
		}

		if ( $args[ self::ARG_SORT ] instanceof Sort ) {
			$list_screens = $args[ self::ARG_SORT ]->sort( $list_screens );
		}

		return $list_screens;
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return ListScreen|null
	 */
	public function find( ListScreenId $id ) {
		foreach ( $this->repositories as $repository ) {
			if ( ! $repository->exists( $id ) ) {
				continue;
			}

			$list_screen = $repository->find( $id );

			if ( ! $list_screen ) {
				continue;
			}

			return $list_screen;
		}

		return null;
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function exists( ListScreenId $id ) {
		return null !== $this->find( $id );
	}

	public function save( ListScreen $list_screen ) {
		$this->update( $list_screen, 'save' );
	}

	public function delete( ListScreen $list_screen ) {
		$this->update( $list_screen, 'delete' );
	}

	private function update( ListScreen $list_screen, $action ) {
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
				switch ( $action ) {
					case 'save':
						$repository->save( $list_screen );

						break;
					case 'delete':
						$repository->delete( $list_screen );

						break;
					default:
						throw new LogicException( 'Invalid action for update call.' );
				}

				return;
			}
		}
	}

}