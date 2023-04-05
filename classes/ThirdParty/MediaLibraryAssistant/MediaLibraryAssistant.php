<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Admin\MenuListItems;
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

		add_action( 'acp/admin/menu_list', [ $this, 'update_menu_list_groups' ] );
	}

	public function update_menu_list_groups( MenuListItems $menu ): void {
		$menu->add( new AC\Admin\Type\MenuListItem(
			'mla-media-assistant',
			__( 'Media Library Assistant' ),
			admin_url(),
			'media'
		) );
	}

}