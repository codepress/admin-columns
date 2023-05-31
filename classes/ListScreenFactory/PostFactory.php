<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\ListScreenFactory;
use AC\PostTypeRepository;
use InvalidArgumentException;
use WP_Screen;

class PostFactory implements ListScreenFactory {

	use ListSettingsTrait;

	private $post_type_repository;

	public function __construct( PostTypeRepository $post_type_repository ) {
		$this->post_type_repository = $post_type_repository;
	}

	protected function create_list_screen( string $key ): Post {
		return new Post( $key );
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		if ( ! $this->can_create( $key ) ) {
			throw new InvalidArgumentException( 'Invalid key.' );
		}

		return $this->add_settings( $this->create_list_screen( $key ), $settings );
	}

	public function create_from_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen {
		if ( ! $this->can_create_from_wp_screen( $screen ) ) {
			throw new InvalidArgumentException( 'Invalid screen.' );
		}

		return $this->create( $screen->post_type, $settings );
	}

	public function can_create( string $key ): bool {
		return post_type_exists( $key ) && $this->is_supported_post_type( $key );
	}

	private function is_supported_post_type( string $post_type ): bool {
		return $this->post_type_repository->exists( $post_type );
	}

	public function can_create_from_wp_screen( WP_Screen $screen ): bool {
		return 'edit' === $screen->base && $screen->post_type && 'edit-' . $screen->post_type === $screen->id && $this->is_supported_post_type( $screen->post_type );
	}

}