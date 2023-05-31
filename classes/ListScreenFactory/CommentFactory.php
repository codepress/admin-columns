<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Comment;
use AC\ListScreenFactory;
use LogicException;
use WP_Screen;

class CommentFactory implements ListScreenFactory {

	use ListSettingsTrait;

	protected function create_list_screen(): Comment {
		return new Comment();
	}

	public function can_create( string $key ): bool {
		return 'wp-comments' === $key;
	}

	public function can_create_from_wp_screen( WP_Screen $screen ): bool {
		return 'edit-comments' === $screen->base && 'edit-comments' === $screen->id;
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		if ( ! $this->can_create( $key ) ) {
			throw new LogicException( 'Invalid key' );
		}

		return $this->add_settings( $this->create_list_screen(), $settings );
	}

	public function create_from_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen {
		if ( ! $this->can_create_from_wp_screen( $screen ) ) {
			throw new LogicException( 'Invalid screen' );
		}

		return $this->add_settings( $this->create_list_screen(), $settings );
	}

}