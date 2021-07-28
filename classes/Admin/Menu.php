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
		$this->items[] = $item;

		return $this;
	}

	public function get_items() {
		return $this->items;
	}

}