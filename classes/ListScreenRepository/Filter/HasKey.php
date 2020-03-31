<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class HasKey implements Filter {

	/**
	 * @var string
	 */
	private $key;

	public function __construct( $key ) {
		$this->key = $key;
	}

	public function filter( ListScreenCollection $list_screens ) {
		$filtered = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->key === $list_screen->get_key() ) {
				$filtered->add( $list_screen );
			}
		}

		return $filtered;
	}

}