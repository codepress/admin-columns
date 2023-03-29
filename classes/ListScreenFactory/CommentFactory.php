<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Comment;
use AC\ListScreenFactoryInterface;
use WP_Screen;

class CommentFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	public function create( string $key, array $settings = [] ): ?ListScreen {
		if ( 'wp-comments' !== $key ) {
			return null;
		}

		return $this->add_settings( new Comment(), $settings );
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		if ( 'edit-comments' === $screen->base && 'edit-comments' === $screen->id ) {
			return $this->add_settings( new Comment(), $settings );
		}

		return null;
	}

}