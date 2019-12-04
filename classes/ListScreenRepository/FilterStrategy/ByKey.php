<?php

namespace AC\ListScreenRepository\FilterStrategy;

use AC\ListScreenCollection;
use AC\ListScreenRepository\FilterStrategy;

class ByKey implements FilterStrategy {

	/** @var string */
	private $key;

	public function __construct( $key ) {
		$this->key = $key;
	}

	public function filter( ListScreenCollection $list_screens ) {
		$filtered = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->key === $list_screen->get_key() ) {
				$filtered->push( $list_screen );
			}
		}

		return $filtered;
	}

}