<?php

namespace AC\Table;

use AC\Preferences\Site;

class ScreenPreferences extends Site {

	public function __construct() {
		parent::__construct( 'screen_preferences' );
	}

}