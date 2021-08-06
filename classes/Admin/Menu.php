<?php

namespace AC\Admin;

class Menu {

	/**
	 * @var Menu\Item[]
	 */
	private $items;

	public function __construct( array $items = [] ) {
		array_map( [ $this, 'add_item' ], $items );
	}

	public function add_item( Menu\Item $item ) {
		$this->items[ $item->get_slug() ] = $item;

		return $this;
	}

	public function get_items() {
		return $this->items;
	}

	public function get_item_by_slug( $slug ) {
		foreach ( $this->items as $item ) {
			if ( $item->get_slug() === $slug ) {
				return $item;
			}
		}

		return null;
	}

}