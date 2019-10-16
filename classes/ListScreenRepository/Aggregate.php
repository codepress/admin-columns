<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenFactory;
use AC\Request;

class Aggregate implements ListScreenRepository {

	/**
	 * @var ListScreenRepository[]
	 */
	private $repositories;

	/** @var ListScreenFactory */
	private $factory;

	public function __construct( ListScreenFactory $factory ) {
		$this->factory = $factory;
	}

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

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$list_screens = new ListScreenCollection();

		foreach ( $this->repositories as $repository ) {
			$list_screens->add_collection( $repository->find_all( $args ) );
		}

		return $this->unique_by_list_id( $list_screens );
	}

	public function find( $id ) {
		foreach ( $this->repositories as $repository ) {
			$list_screen = $repository->find( $id );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
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

	public function delete( $id ) {
		foreach ( $this->repositories as $repository ) {
			if ( $repository instanceof Write ) {
				$repository->delete( $id );
			}
		}
	}

	/**
	 * @param Request $request
	 *
	 * @return ListScreen|null
	 */
	public function find_by_request( Request $request ) {
		$layout_id = $request->get( 'layout' );

		return $this->find( $layout_id );
	}

	private function unique_by_list_id( ListScreenCollection $collection ) {
		$list_screens = [];

		/**
		 * @var ListScreen $list_screen
		 */
		foreach ( $collection as $list_screen ) {
			if ( ! isset( $list_screens[ $list_screen->get_layout_id() ] ) ) {
				$list_screens[ $list_screen->get_layout_id() ] = $list_screen;
				continue;
			}

			$existing_ls = $list_screens[ $list_screen->get_layout_id() ];

			if ( $list_screen->get_updated() > $existing_ls->get_updated() ) {
				$list_screens[ $list_screen->get_layout_id() ] = $list_screen;
			}
		}

		return new ListScreenCollection( $list_screens );
	}

}