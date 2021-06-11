<?php

namespace AC\Admin;

use AC\Admin;

class Menu {

	/**
	 * @var Admin\Menu\Item[]
	 */
	private $items;

	public function add_item( Admin\Menu\Item $item ) {
		$this->items[] = $item;

		return $this;
	}

	public function get_items() {
		return $this->items;
	}

}