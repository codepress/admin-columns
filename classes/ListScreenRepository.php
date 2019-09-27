<?php
namespace AC;

use AC\ListScreenRepository\Read;
use AC\ListScreenRepository\Write;

class ListScreenRepository implements Read, Write {

	/** @var Read[] */
	private $repositories_read;

	/** @var Write[] */
	private $repositories_write;

	/** @var ListScreenFactory */
	private $factory;

	public function __construct( array $repositories, ListScreenFactory $factory ) {
		array_map( [ $this, 'add_repository_read' ], $repositories );
		array_map( [ $this, 'add_repository_write' ], $repositories );

		$this->factory = $factory;
	}

	private function add_repository_read( $repository ) {
		if ( $repository instanceof Read ) {
			$this->repositories_read[] = $repository;
		}
	}

	private function add_repository_write( $repository ) {
		if ( $repository instanceof Write ) {
			$this->repositories_write[] = $repository;
		}
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreen[]
	 */
	public function query( array $args ) {
		$list_screens = [];

		foreach ( $this->repositories_read as $repository ) {
			$list_screens = $repository->query( $args ) + $list_screens;
		}

		return array_filter( $list_screens );
	}

	public function find_by_id( $id ) {
		foreach ( $this->repositories_read as $repository ) {
			$list_screen = $repository->find_by_id( $id );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

	public function exists( $id ) {
		return null !== $this->find_by_id( $id );
	}

	public function save( ListScreen $list_screen ) {
		foreach ( $this->repositories_write as $repository ) {
			$repository->save( $list_screen );
		}
	}

	public function delete( $id ) {
		foreach ( $this->repositories_write as $repository ) {
			$repository->delete( $id );
		}
	}

}