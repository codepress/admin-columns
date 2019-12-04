<?php

namespace AC\ListScreenRepository\SortStrategy;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\SortStrategy;

class ListScreenLabel implements SortStrategy {

	public function sort( ListScreenCollection $list_screens ) {
		if ( $list_screens->count() < 1 ) {
			return $list_screens;
		}

		$grouped = [];

		/** @var ListScreen $list_screen */
		foreach ( $list_screens as $list_screen ) {
			$grouped[ $list_screen->get_label() ][] = $list_screen;
		}

		ksort( $grouped );

		$ordered = new ListScreenCollection();

		foreach ( $grouped as $_list_screens ) {
			foreach ( $_list_screens as $_list_screen ) {
				$ordered->push( $_list_screen );
			}
		}

		return $ordered;
	}

}