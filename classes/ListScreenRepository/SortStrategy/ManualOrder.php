<?php

namespace AC\ListScreenRepository\SortStrategy;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\SortStrategy;
use AC\Storage;

class ManualOrder implements SortStrategy {

	/** @var Storage\ListScreenOrder(); */
	private $listScreenOrder;

	public function __construct() {
		$this->listScreenOrder = new Storage\ListScreenOrder();
	}

	public function sort( ListScreenCollection $listScreens ) {
		if ( $listScreens->count() < 1 ) {
			return $listScreens;
		}

		$key = $listScreens->current()->get_key();

		$_listScreens = [];

		/** @var ListScreen $listScreen */
		foreach ( $listScreens as $listScreen ) {
			$_listScreens[ $listScreen->get_layout_id() ] = $listScreen;
		}

		$ordered = new ListScreenCollection();

		foreach ( $this->listScreenOrder->get( $key ) as $id ) {
			if ( ! isset( $_listScreens[ $id ] ) ) {
				continue;
			}

			$ordered->push( $_listScreens[ $id ] );

			unset( $_listScreens[ $id ] );
		}

		$ordered->add_collection( new ListScreenCollection( $_listScreens ) );

		return $ordered;
	}

}