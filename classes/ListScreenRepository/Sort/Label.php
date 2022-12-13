<?php

namespace AC\ListScreenRepository\Sort;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Sort;

class Label implements Sort {

	public function sort( ListScreenCollection $list_screens ): ListScreenCollection {
		$labels = [];

		foreach ( $list_screens as $list_screen ) {
			$labels[ $list_screen->get_label() ][] = $list_screen;
		}

		ksort( $labels );

		$labels = array_values( $labels );

		return new ListScreenCollection( array_merge( [], ...$labels ) );
	}

}