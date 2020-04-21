<?php

namespace AC\Admin\Preference;

use AC\Preferences\User;

class ScreenOptions extends User {

	public function __construct() {
		parent::__construct( 'admin_screen_options' );
	}

}