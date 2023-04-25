<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
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

		add_action( 'ac/admin/menu_list', [ $this, 'add_menu_item' ] );
	}

	public function add_menu_item( AC\Admin\MenuListItems $menu ) {
		$menu->add(
			new AC\Admin\Type\MenuListItem(
				'mla-media-assistant',
				__( 'Media Library Assistant', 'codepress-admin-columns' ),
				'media'
			)
		);
	}

}