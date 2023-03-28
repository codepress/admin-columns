<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\ListScreenFactoryInterface;
use WP_Screen;

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

	public function create_by_wp_screen( WP_Screen $screen, array $settings ): ?ListScreen {
		if ( 'edit' === $screen->base && $screen->post_type ) {
			return $this->create( $screen->post_type, $settings );
		}

		return null;
	}

}