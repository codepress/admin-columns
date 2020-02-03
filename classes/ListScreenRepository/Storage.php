<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Storage\OrderedList;

final class Storage {

	/**
	 * @var OrderedList
	 */
	private $repositories;

	public function __construct() {
		$this->repositories = new OrderedList();
	}

	/**
	 * @return OrderedList
	 */
	public function get_repositories() {
		return $this->repositories;
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
	 * @param $id
	 *
	 * @return ListScreen|null
	 */
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
			$match = ! $repository->has_rules() || $repository->get_rules()->match( $list_screen );

			if ( $match && $repository->is_writable() ) {
				$repository->save( $list_screen );

				return;
			}
		}
	}

	public function delete( ListScreen $list_screen ) {
		foreach ( $this->repositories as $repository ) {
			if ( $repository->is_writable() ) {
				$repository->delete( $list_screen );
			}
		}
	}

}