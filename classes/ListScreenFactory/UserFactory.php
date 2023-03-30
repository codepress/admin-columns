<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\User;
use AC\ListScreenFactoryInterface;
use WP_Screen;

class UserFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	protected function create_list_screen(): User {
		return new User();
	}

	public function create( string $key, array $settings = [] ): ?ListScreen {
		if ( 'wp-users' === $key ) {
			return $this->add_settings( $this->create_list_screen(), $settings );
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		if ( 'delete' === filter_input( INPUT_GET, 'action' ) ) {
			return null;
		}

		if ( 'users' === $screen->base && 'users' === $screen->id ) {
			return $this->add_settings( $this->create_list_screen(), $settings );
		}

		return null;
	}

}