<?php

declare( strict_types=1 );

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\ListScreen;
use AC\ThirdParty\MediaLibraryAssistant\ListScreen\MediaLibrary;
use MLACore;
use WP_Screen;

class ListScreenFactory extends AC\ListScreenFactory\BaseFactory {

	public function can_create( string $key ): bool {
		return 'mla-media-assistant' === $key;
	}

	public function can_create_from_wp_screen( WP_Screen $screen ): bool {
		return class_exists( 'MLACore' ) && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id;
	}

	protected function create_list_screen( string $key ): ListScreen {
		return new MediaLibrary();
	}

	protected function create_list_screen_from_wp_screen( WP_Screen $screen ): ListScreen {
		return $this->create_list_screen( 'mla-media-assistant' );
	}

}