<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Admin\MenuListItems;
use AC\Admin\Type\MenuListItem;
use AC\Registerable;

class MediaLibraryAssistant implements Registerable {

	public function register() {
		if ( ! defined( 'MLA_PLUGIN_PATH' ) ) {
			return;
		}

		if ( method_exists( 'MLACore', 'register_list_screen' ) ) {
			remove_action( 'ac/list_screens', 'MLACore::register_list_screen' );
		}

		AC\ListScreenFactory::add( new ListScreenFactory() );

		// TODO replace with 'ac/admin/menu_group'
		add_action( 'ac/admin/menu_list', [ $this, 'update_menu_list_groups' ] );
	}

	public function update_menu_list_groups( MenuListItems $menu ): void {
		$menu->add(
			new MenuListItem(
				'mla-media-assistant',
				__( 'Media Library Assistant' ),
				'media'
			)
		);
	}

}