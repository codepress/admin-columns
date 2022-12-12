<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class ListKey implements Filter {

	private $key;

	public function __construct( string $key ) {
		$this->key = $key;
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		$filtered = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->key === $list_screen->get_key() ) {
				$filtered->add( $list_screen );
			}
		}

		return $filtered;
	}

}