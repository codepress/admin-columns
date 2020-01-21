<?php

namespace AC\ListScreenRepository\Sort;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Sort;

class ListScreenLabel implements Sort {

	public function sort( ListScreenCollection $list_screens ) {
		// TODO David make more elegant and simpler _ ?
		$grouped = [];

		foreach ( $list_screens as $list_screen ) {
			$grouped[ $list_screen->get_label() ][] = $list_screen;
		}

		ksort( $grouped );

		$ordered = new ListScreenCollection();

		foreach ( $grouped as $_list_screens ) {
			foreach ( $_list_screens as $_list_screen ) {
				$ordered->add( $_list_screen );
			}
		}

		return $ordered;
	}

}