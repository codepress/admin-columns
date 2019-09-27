<?php
namespace AC;

class ListScreenRepository {

	/** @var Data[] */
	private $storages;

	/** @var ListScreenFactory */
	private $factory;

	public function __construct( array $storages, ListScreenFactory $factory ) {
		$this->storages = $storages;
		$this->factory = $factory;
	}

	public function find_all( $args ) {
		$list_screens = [];

		foreach ( $this->storages as $storage ) {
			$results = $storage->query( $args );

			foreach ( $results as $data ) {
				$list_screens[] = $this->factory->create( $data->type, $data );
			}
		}

		return $list_screens;
	}

	public function find_by_id( $id ) {
		$list_screens = [];

		foreach ( $this->storages as $storage ) {
			$data = $storage->find_by_id( $id );

			if ( $data ) {
				return $this->factory->create( $data->type, $data );
			}
		}

		return null;
	}

}