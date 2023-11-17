<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\Table\TableScreens;
use AC\Type\ListKey;

class MediaLibraryAssistant implements Registerable {

	public function register(): void
    {
		if ( ! defined( 'MLA_PLUGIN_PATH' ) ) {
			return;
		}

        AC\TableScreenFactory\Aggregate::add(new TableScreenFactory());

        // TODO
		add_action( 'ac/list_keys', [ $this, 'add_list_keys' ] );
	}

	public function add_list_keys( TableScreens $table_screens ): void {
        $table_screens->add( new TableScreen() );
	}

}