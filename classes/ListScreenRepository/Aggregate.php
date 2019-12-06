<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;

class Aggregate implements ListScreenRepository {

	/**
	 * @var ListScreenRepository[]
	 */
	private $repositories;

	public function register_repository( ListScreenRepository $repository ) {
		$this->repositories[ get_class( $repository ) ] = $repository;

		return $this;
	}

	public function deregister_repository( ListScreenRepository $repository ) {
		if ( isset( $this->repositories[ get_class( $repository ) ] ) ) {
			unset( $this->repositories[ get_class( $repository ) ] );
		}

		return $this;
	}

	public function get_repositories() {
		return $this->repositories;
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$list_screens = new ListScreenCollection();

		$sort_strategy = isset( $args['sort'] )
			? $args['sort']
			: false;

		$filter_strategy = isset( $args['filter'] )
			? $args['filter']
			: false;

		// does not apply to repositories
		unset( $args['sort'], $args['filter'] );

		foreach ( $this->repositories as $repository ) {
			$list_screens->add_collection( $repository->find_all( $args ) );
		}

		if ( $filter_strategy instanceof FilterStrategy ) {
			$list_screens = $filter_strategy->filter( $list_screens );
		}

		$list_screens = $this->filter_unique( $list_screens );

		if ( $sort_strategy instanceof SortStrategy ) {
			$list_screens = $sort_strategy->sort( $list_screens );
		}

		return $list_screens;
	}

	public function find( $id ) {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			$list_screen = $repository->find( $id );

			if ( $list_screen ) {
				$list_screens->push( $list_screen );
			}
		}

		$list_screen = $this->filter_unique( $list_screens )->current();

		if ( ! $list_screen ) {
			return null;
		}

		return $list_screen;
	}

	/**
	 * Creates an unique set of list screens with no duplicate Id's. When a duplicate Id is found it will
	 * keep the on with a higher modified date.
	 *
	 * @param ListScreenCollection $list_screens
	 *
	 * @return ListScreenCollection
	 */
	private function filter_unique( ListScreenCollection $list_screens ) {
		$unique = new ListScreenCollection();

		/** @var ListScreen $list_screen */
		foreach ( $list_screens as $list_screen ) {
			if ( $unique->has( $list_screen->get_layout_id() ) ) {

				/** @var ListScreen $_list_screen */
				$_list_screen = $unique->get( $list_screen->get_layout_id() );

				// Use the list screen that is newer
				if ( $_list_screen->get_updated() > $list_screen->get_updated() ) {
					continue;
				}

				// Use the `read only` list screen when the dates are the same
				if ( $_list_screen->get_updated() == $list_screen->get_updated() && $_list_screen->is_read_only() ) {
					continue;
				}
			}

			$unique->put( $list_screen->get_layout_id(), $list_screen );
		}

		return $unique;
	}

	public function exists( $id ) {
		return null !== $this->find( $id );
	}

	public function save( ListScreen $list_screen ) {
		foreach ( $this->repositories as $repository ) {
			if ( $repository instanceof Write ) {
				$repository->save( $list_screen );
			}
		}
	}

	public function delete( ListScreen $list_screen ) {
		foreach ( $this->repositories as $repository ) {
			if ( $repository instanceof Write ) {
				$repository->delete( $list_screen );
			}
		}
	}

}