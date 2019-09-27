<?php
namespace AC;

class ListScreenRepository implements Data {

	/** @var Data[] */
	private $storages;

	/** @var ListScreenFactory */
	private $factory;

	public function __construct( array $storages, ListScreenFactory $factory ) {
		$this->storages = $storages;
		$this->factory = $factory;
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreen[]
	 */
	public function query( array $args ) {
		$list_screens = [];

		foreach ( $this->storages as $storage ) {
			$list_screens = $storage->query( $args ) + $list_screens;
		}

		return array_filter( $list_screens );
	}

	public function find_by_id( $id ) {
		foreach ( $this->storages as $storage ) {
			$list_screen = $storage->find_by_id( $id );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

	public function save( ListScreen $data ) {
		// todo
	}

	public function delete( $id ) {
		// todo
	}

	public function exists( $id ) {
		// todo
	}

}