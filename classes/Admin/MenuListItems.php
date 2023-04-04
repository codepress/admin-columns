<?php
declare( strict_types=1 );

namespace AC\Admin;

use AC\Admin\Type\MenuListItem;

class MenuListItems {

	/**
	 * @var MenuListItem[]
	 */
	private $items = [];

	public function __construct( array $items = [] ) {
		array_map( [ $this, 'add' ], $items );
	}

	public function add( MenuListItem $item ): void {
		$this->items[ $item->get_key() ] = $item;
	}

	public function get( string $key ): ?MenuListItem {
		return $this->items[ $key ] ?? null;
	}

	public function remove( MenuListItem $item ): void {
		unset( $this->items[ $item->get_key() ] );
	}

	/**
	 * @return MenuListItem[]
	 */
	public function all(): array {
		return $this->items;
	}

}