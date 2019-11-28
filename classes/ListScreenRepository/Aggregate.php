<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Request;

class Aggregate implements ListScreenRepository, SourceAware {

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

		// does not apply to repositories
		unset( $args['sort'] );

		foreach ( $this->repositories as $repository ) {
			$list_screens->add_collection( $repository->find_all( $args ) );
		}

		$list_screens = $list_screens->filter_unique();

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

		$list_screen = $list_screens->filter_unique()->current();

		if ( ! $list_screen ) {
			return null;
		}

		return $list_screen;
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

	/**
	 * @param Request $request
	 *
	 * @return ListScreen|null
	 */
	public function find_by_request( Request $request ) {
		return $this->find( $request->get( 'layout' ) );
	}

	public function getSource( ListScreen $listScreen ) {
		foreach ( $this->repositories as $repository ) {
			if ( $repository instanceof SourceAware ) {
				$source = $repository->getSource( $listScreen );

				if ( $source ) {
					break;
				}
			}
		}

		return null;
	}

}