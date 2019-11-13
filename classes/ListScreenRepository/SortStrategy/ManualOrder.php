<?php

namespace AC\ListScreenRepository\SortStrategy;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\SortStrategy;
use AC\Storage;

class ManualOrder implements SortStrategy {

	/** @var Storage\ListScreenOrder(); */
	private $list_screen_order;

	public function __construct() {
		$this->list_screen_order = new Storage\ListScreenOrder();
	}

	public function sort( ListScreenCollection $list_screens ) {
		if ( $list_screens->count() < 1 ) {
			return $list_screens;
		}

		$key = $list_screens->current()->get_key();

		$_list_screens = [];

		/** @var ListScreen $list_screen */
		foreach ( $list_screens as $list_screen ) {
			$_list_screens[ $list_screen->get_layout_id() ] = $list_screen;
		}

		$ordered = new ListScreenCollection();

		foreach ( $this->list_screen_order->get( $key ) as $id ) {
			if ( ! isset( $_list_screens[ $id ] ) ) {
				continue;
			}

			$ordered->push( $_list_screens[ $id ] );

			unset( $_list_screens[ $id ] );
		}

		$ordered->add_collection( new ListScreenCollection( $_list_screens ) );

		return $ordered;
	}

}