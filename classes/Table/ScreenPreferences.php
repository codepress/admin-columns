<?php

namespace AC\Table;

use AC\Preferences\Site;

class ScreenPreferences extends Site {

	public function __construct( $user_id = null ) {
		parent::__construct( 'screen_preferences', $user_id );
	}

}