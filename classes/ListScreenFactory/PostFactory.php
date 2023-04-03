<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\ListScreenFactoryInterface;
use InvalidArgumentException;
use WP_Screen;

class PostFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	protected function create_list_screen( string $key ): Post {
		return new Post( $key );
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		if ( ! $this->can_create( $key ) ) {
			throw new InvalidArgumentException( 'Invalid key.' );
		}

		return $this->add_settings( $this->create_list_screen( $key ), $settings );
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen {
		if ( ! $this->can_create_by_wp_screen( $screen ) ) {
			throw new InvalidArgumentException( 'Invalid screen.' );
		}

		return $this->create( $screen->post_type, $settings );
	}

	public function can_create( string $key ): bool {
		return post_type_exists( $key );
	}

	public function can_create_by_wp_screen( WP_Screen $screen ): bool {
		return 'edit' === $screen->base && $screen->post_type && 'edit-' . $screen->post_type === $screen->id;
	}

}