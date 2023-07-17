<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\Table\ListKeyCollection;
use AC\Type\ListKey;

class MediaLibraryAssistant implements Registerable {

	public function register(): void
    {
		if ( ! defined( 'MLA_PLUGIN_PATH' ) ) {
			return;
		}

		AC\ListScreenFactory\Aggregate::add( new ListScreenFactory() );
		add_action( 'ac/list_keys', [ $this, 'add_list_keys' ] );
	}

	public function add_list_keys( ListKeyCollection $list_keys ): void {
		$list_keys->add( new ListKey( 'mla-media-assistant' ) );
	}

}