<?php
declare( strict_types=1 );

namespace AC\Admin;

use AC\Admin\Type\MenuListItem;

class MenuListItems {

	private $items = [];

	public function __construct( array $items = [] ) {
		array_map( [ $this, 'add' ], $items );
	}

	public function add( MenuListItem $item ): void {
		$this->items[] = $item;
	}

	/**
	 * @return MenuListItem[]
	 */
	public function all(): array {
		return $this->items;
	}

}