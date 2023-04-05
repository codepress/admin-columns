<?php
declare( strict_types=1 );

namespace AC\Admin;

class SupportedListScreens {

	private $menu_list_factory;

	public function __construct( MenuListFactory $menu_list_factory ) {
		$this->menu_list_factory = $menu_list_factory;
	}

	/**
	 * @return string[]
	 */
	public function find_all(): array {
		$keys = [];

		foreach ( $this->menu_list_factory->create()->all() as $item ) {
			$keys[] = $item->get_key();
		}

		return $keys;
	}

}