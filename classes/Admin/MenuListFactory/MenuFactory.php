<?php
declare( strict_types=1 );

namespace AC\Admin\MenuListFactory;

use AC\Admin\MenuListFactory;
use AC\Admin\MenuListItems;
use AC\Admin\Type\MenuListItem;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Table\ListKeysFactoryInterface;

class MenuFactory implements MenuListFactory {

	private $list_keys_factory;

	private $list_screen_factory;

	public function __construct( ListKeysFactoryInterface $factory, ListScreenFactory $list_screen_factory ) {
		$this->list_keys_factory = $factory;
		$this->list_screen_factory = $list_screen_factory;
	}

	private function create_menu_item( ListScreen $list_screen ): MenuListItem {
		$group = (string) apply_filters( 'ac/admin/menu_group', $list_screen->get_group(), $list_screen );

		return new MenuListItem(
			$list_screen->get_key(),
			$list_screen->get_label(),
			$group ?: 'other'
		);
	}

	public function create(): MenuListItems {
		$menu = new MenuListItems();

		foreach ( $this->list_keys_factory->create()->all() as $list_key ) {
			if ( $list_key->is_network() ) {
				continue;
			}

			if ( $this->list_screen_factory->can_create( (string) $list_key ) ) {
				$menu->add( $this->create_menu_item( $this->list_screen_factory->create( (string) $list_key ) ) );
			}
		}

		do_action( 'ac/admin/menu_list', $menu );

		return $menu;
	}

}