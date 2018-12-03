<?php
namespace AC\Admin;

abstract class Menu {

	/**
	 * @var Page[]
	 */
	private $items = array();

	public function register( Page $item ) {
		$this->items[] = $item;

		return $this;
	}

	public function get_items() {
		return $this->items;
	}

}