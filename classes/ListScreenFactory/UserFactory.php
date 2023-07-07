<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\User;
use WP_Screen;

class UserFactory extends BaseFactory {

	protected function create_list_screen( string $key ): ListScreen {
		return new User();
	}

	protected function create_list_screen_from_wp_screen( WP_Screen $screen ): ListScreen {
		return $this->create_list_screen( 'wp-users' );
	}

	public function can_create( string $key ): bool {
		return 'wp-users' === $key;
	}

	public function can_create_from_wp_screen( WP_Screen $screen ): bool {
		return 'delete' !== filter_input( INPUT_GET, 'action' ) && 'users' === $screen->base && 'users' === $screen->id;
	}

}