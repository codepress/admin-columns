<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Comment;
use AC\ListScreenFactoryInterface;
use WP_Screen;

class CommentFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	protected function create_list_screen(): Comment {
		return new Comment();
	}

	public function create( string $key, array $settings = [] ): ?ListScreen {
		if ( 'wp-comments' === $key ) {
			return $this->add_settings( $this->create_list_screen(), $settings );
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		if ( 'edit-comments' === $screen->base && 'edit-comments' === $screen->id ) {
			return $this->add_settings( $this->create_list_screen(), $settings );
		}

		return null;
	}

}