<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\Table\ListKeyCollection;
use AC\Type\ListKey;

class MediaLibraryAssistant implements Registerable {

	public function register() {
		if ( ! defined( 'MLA_PLUGIN_PATH' ) ) {
			return;
		}

		if ( method_exists( 'MLACore', 'register_list_screen' ) ) {
			remove_action( 'ac/list_screens', 'MLACore::register_list_screen' );
		}

		AC\ListScreenFactory::add( new ListScreenFactory() );
		add_action( 'ac/list_keys', [ $this, 'add_list_keys' ] );
	}

	public function add_list_keys( ListKeyCollection $list_keys ): void {
		$list_keys->add( new ListKey( 'mla-media-assistant' ) );
	}

}