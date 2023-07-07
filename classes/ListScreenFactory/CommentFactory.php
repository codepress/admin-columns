<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Comment;
use WP_Screen;

class CommentFactory extends BaseFactory {

	protected function create_list_screen( string $key ): ListScreen {
		return new Comment();
	}

	protected function create_list_screen_from_wp_screen( WP_Screen $screen ): ListScreen {
		return $this->create_list_screen( 'wp-comments' );
	}

	public function can_create( string $key ): bool {
		return 'wp-comments' === $key;
	}

	public function can_create_from_wp_screen( WP_Screen $screen ): bool {
		return 'edit-comments' === $screen->base && 'edit-comments' === $screen->id;
	}

}