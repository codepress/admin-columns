<?php

namespace AC\Admin;

use AC\Admin\Menu\Item;
use AC\Collection;
use AC\Renderable;
use AC\View;

class Menu extends Collection implements Renderable {

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

	public function render() {
		$view = new View( [
			'menu_items' => $this->items,
		] );

		return $view->set_template( 'admin/menu' )->render();
	}

}