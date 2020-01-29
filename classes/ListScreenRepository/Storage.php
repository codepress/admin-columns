<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use LogicException;

final class Storage {

	const DATABASE = 'database';
	const FILE = 'file';

	/**
	 * @var ListScreenRepository[]
	 */
	private $repositories;

	/**
	 * @var ListScreenRepository\Writable
	 */
	private $write_engine;

	// TODO David needs a write engine

	public function register_repository( ListScreenRepository $repository ) {
		$this->repositories[ get_class( $repository ) ] = $repository;

		if ( $is_write_engine ) {
			if ( ! $repository instanceof ListScreenRepository\Writable ) {
				throw new LogicException( 'Trying to register a storage engine that does not implement the %s interface.', ListScreenRepository\Writable::class );
			}

			$this->write_engine = $repository;
		}

		return $this;
	}

	/**
	 * @param array       $args
	 * @param Filter|null $filtering
	 * @param Sort|null   $sorting
	 *
	 * @return ListScreenCollection
	 */

	// TODO David filters maybe NOT here, or ALSO here? Think about htis some mor
	public function find_all( array $args = [], Filter $filtering = null, Sort $sorting = null ) {
		$list_screens = $this->write_engine->find_all( $args );

		foreach ( $this->repositories as $repository ) {
			if ( $repository === $this->write_engine ) {
				continue;
			}

			foreach ( $repository->find_all( $args ) as $list_screen ) {
				if ( ! $list_screens->contains( $list_screen ) ) {
					$list_screens->add( $list_screen );
				}
			}
		}

		// TODO DAvid make this into traits
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
		$list_screen = $this->write_engine->find( $id );

		if ( $list_screen ) {
			return $list_screen;
		}

		foreach ( $this->repositories as $repository ) {
			if ( $repository === $this->write_engine ) {
				continue;
			}

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
		$this->write_engine->save( $list_screen );
	}

	public function delete( ListScreen $list_screen ) {
		$this->write_engine->delete( $list_screen );
	}

}