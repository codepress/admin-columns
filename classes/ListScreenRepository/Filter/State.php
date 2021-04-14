<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use ACP\Settings\ListScreen\ActiveState;

class State implements Filter {

	public function filter( ListScreenCollection $list_screens ) {
		foreach ( clone $list_screens as $list_screen ) {
			if ( ! ActiveState::create( $list_screen )->is_active() ) {
				$list_screens->remove( $list_screen );
			}
		}

		return $list_screens;
	}

}