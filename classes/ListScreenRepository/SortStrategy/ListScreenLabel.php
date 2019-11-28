<?php

namespace AC\ListScreenRepository\SortStrategy;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\SortStrategy;

class ListScreenLabel implements SortStrategy {

	public function sort( ListScreenCollection $listScreens ) {
		if ( $listScreens->count() < 1 ) {
			return $listScreens;
		}

		$grouped = [];

		/** @var ListScreen $listScreen */
		foreach ( $listScreens as $listScreen ) {
			$grouped[ $listScreen->get_label() ][] = $listScreen;
		}

		ksort( $grouped );

		$ordered = new ListScreenCollection();

		foreach ( $grouped as $_listScreens ) {
			foreach ( $_listScreens as $_listScreen ) {
				$ordered->push( $_listScreen );
			}
		}

		return $ordered;
	}

}