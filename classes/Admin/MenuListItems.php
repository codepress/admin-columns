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
		$this->items[] = $item;
	}

	public function find_by_key( string $key ): ?MenuListItem {
		foreach ( $this->items as $item ) {
			if ( $item->get_key() === $key ) {
				return $item;
			}
		}

		return null;
	}

	public function remove( MenuListItem $item ): void {
		foreach ( $this->items as $k => $_item ) {
			if ( $item->get_key() === $_item->get_key() ) {
				unset( $this->items[ $k ] );
			}
		}
	}

	/**
	 * @return MenuListItem[]
	 */
	public function all(): array {
		return $this->items;
	}

}