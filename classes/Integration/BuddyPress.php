<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\Screen;

final class BuddyPress extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-buddypress/ac-addon-buddypress.php',
			__( 'BuddyPress', 'codepress-admin-columns' ),
			'assets/images/addons/buddypress.png',
			__( 'Display any of your Profile Fields for BuddyPress on your users overview.', 'codepress-admin-columns' )
		);
	}

	public function is_plugin_active() {
		return class_exists( 'BuddyPress', false );
	}

	public function show_notice( Screen $screen ) {
		return 'users' === $screen->get_id();
	}

	public function show_placeholder( ListScreen $list_screen ) {
		return $list_screen instanceof ListScreen\User;
	}

}