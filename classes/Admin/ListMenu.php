<?php
declare( strict_types=1 );

namespace AC\Admin;

// TODO collection Options
class ListMenu {

	private $items;

	public function __construct( array $items ) {
		$this->items = $items;
	}

	public function add( string $list_key, string $label ): void {
		$this->items[ $list_key ] = $label;
	}

	public function get_items(): array {
		return $this->items;
	}

}