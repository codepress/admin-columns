<?php

namespace AC\ListScreenRepository\Sort;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Sort;

class ListIds implements Sort {

	private $list_ids;

	public function __construct( array $list_ids = [] ) {
		$this->list_ids = $list_ids;
	}

	public function sort( ListScreenCollection $list_screens ): ListScreenCollection {
		if ( ! $list_screens->count() ) {
			return $list_screens;
		}

		$lists = [];

		foreach ( $list_screens as $list_screen ) {
			$lists[ $list_screen->get_layout_id() ] = $list_screen;
		}

		$ordered = new ListScreenCollection();

		foreach ( $this->list_ids as $list_id ) {
			if ( ! isset( $lists[ $list_id ] ) ) {
				continue;
			}

			$ordered->add( $lists[ $list_id ] );

			unset( $lists[ $list_id ] );
		}

		foreach ( $lists as $list_screen ) {
			$ordered->add( $list_screen );
		}

		return $ordered;
	}

}