<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class Network implements Filter {

	const KEYS = [
		'wp-ms_sites',
		'wp-ms_users',
	];

	public function filter( ListScreenCollection $list_screens ) {
		foreach ( $list_screens as $list_screen ) {
			if ( ! in_array( $list_screen->get_key(), self::KEYS ) ) {
				$list_screens->remove( $list_screen );
			}
		}

		return $list_screens;
	}

}