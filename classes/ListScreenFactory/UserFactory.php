<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\User;
use AC\ListScreenFactoryInterface;
use WP_Screen;

class UserFactory implements ListScreenFactoryInterface {

	public const KEY = 'wp-users';

	use ListSettingsTrait;

	public function create( string $key, array $settings ): ?ListScreen {
		if ( self::KEY !== $key ) {
			return null;
		}

		return $this->add_settings( new User(), $settings );
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings ): ?ListScreen {
		if ( 'users' === $screen->base && 'users' === $screen->id ) {
			return $this->add_settings( new User(), $settings );
		}

		return null;
	}

}