<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\Type\ListScreenId;
use LogicException;

final class Storage implements ListScreenRepository {

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
	 * @param array       $args
	 * @param Filter|null $filtering
	 * @param Sort|null   $sorting
	 *
	 * @return ListScreenCollection
	 */

	// TODO David decide on $args onoly or Filter / Sort
	public function find_all( array $args = [], Filter $filtering = null, Sort $sorting = null ) {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			foreach ( $repository->find_all( $args ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		if ( $filtering ) {
			$list_screens = $filtering->filter( $list_screens );
		}

		if ( $sorting ) {
			$list_screens = $sorting->sort( $list_screens );
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
			$match = ! $repository->has_rules() || $repository->get_rules()->match( $list_screen );

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