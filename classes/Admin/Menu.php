<?php

namespace AC\Admin;

use AC\Admin\Menu\Item;
use AC\Collection;

class Menu extends Collection {

	public function __construct( array $items = [] ) {
		array_map( [ $this, 'add' ], $items );
	}

	/**
	 * @return Item[]
	 */
	public function all() {
		return parent::all();
	}

	public function add( Item $item ) {
		$this->push( $item );

		return $this;
	}

}