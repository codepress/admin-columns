<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;

final class Storage {

	/**
	 * @var Storage\ListScreenRepository[]
	 */
	private $repositories = [];

	public function register_repository( Storage\ListScreenRepository $repository ) {
		$this->repositories[] = $repository;
	}

	public function set_repositories( array $repositories ) {
		foreach ( $repositories as $repository ) {
			$this->register_repository( $repository );
		}
	}

	/**
	 * @return Storage\ListScreenRepository[]
	 */
	public function get_repositories() {
		$repositories = [];

		foreach ( $this->repositories as $repository ) {
			$repositories[ $repository->get_key() ] = $repository;
		}

		return array_reverse( $repositories );
	}

	/**
	 * Returns the repositories last in, first out
	 *
	 * @return Storage\ListScreenRepository[]
	 */
	private function get_repositories_lifo() {
		return array_reverse( $this->repositories );
	}

	/**
	 * @param array       $args
	 * @param Filter|null $filtering
	 * @param Sort|null   $sorting
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [], Filter $filtering = null, Sort $sorting = null ) {
		$list_screens = new ListScreenCollection();

		foreach ( $this->get_repositories_lifo() as $repository ) {
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
	 * @param $id
	 *
	 * @return ListScreen|null
	 */
	public function find( $id ) {
		foreach ( $this->get_repositories_lifo() as $repository ) {
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

	public function exists( $id ) {
		return null !== $this->find( $id );
	}

	public function save( ListScreen $list_screen ) {
		foreach ( $this->get_repositories_lifo() as $repository ) {
			$match = ! $repository->has_rules() || $repository->get_rules()->match( $list_screen );

			if ( $match && $repository->is_writable() ) {
				$repository->save( $list_screen );

				return;
			}
		}
	}

	public function delete( ListScreen $list_screen ) {
		foreach ( $this->get_repositories_lifo() as $repository ) {
			if ( $repository->is_writable() ) {
				$repository->delete( $list_screen );

				return;
			}
		}
	}

}