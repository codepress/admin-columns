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

		add_action( 'ac/list_screens', [ $this, 'register_list_screens' ] );
	}

	public function register_list_screens(): void {
		AC\ListScreenTypes::instance()->register_list_screen( new ListScreen\MediaLibrary() );
	}

}