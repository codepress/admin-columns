<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

// TODO Stefan remove if not required
final class ListScreenKeys implements Filter {

	private $keys;

	public function __construct( array $keys ) {
		$this->keys = $keys;
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		$collection = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( in_array( $list_screen->get_key(), $this->keys, true ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

}