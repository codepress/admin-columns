<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class ExcludeNetwork implements Filter {

	public function filter( ListScreenCollection $list_screens ) {
		$collection = new ListScreenCollection();
		foreach ( $list_screens as $list_screen ) {
			if ( ! in_array( $list_screen->get_key(), Network::KEYS, true ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

}