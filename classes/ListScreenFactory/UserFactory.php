<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\User;
use AC\ListScreenFactoryInterface;

class UserFactory implements ListScreenFactoryInterface {

	public const KEY = 'wp-users';

	use ListSettingsTrait;

	public function create( string $key, array $settings ): ?ListScreen {
		if ( self::KEY !== $key ) {
			return null;
		}

		return $this->add_settings(
			new User(),
			$settings
		);
	}

}