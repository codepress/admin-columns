<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\ListScreenFactoryInterface;

class PostFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	public function create( string $key, array $settings ): ?ListScreen {
		if ( ! post_type_exists( $key ) ) {
			return null;
		}

		return $this->add_settings(
			new Post( $key ),
			$settings
		);
	}

}