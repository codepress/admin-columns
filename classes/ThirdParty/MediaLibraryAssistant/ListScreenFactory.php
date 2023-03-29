<?php
declare( strict_types=1 );

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC\ListScreen;
use AC\ListScreenFactoryInterface;
use AC\ThirdParty\MediaLibraryAssistant\ListScreen\MediaLibrary;
use MLACore;
use WP_Screen;

class ListScreenFactory implements ListScreenFactoryInterface {

	public function create( string $key, array $settings = [] ): ?ListScreen {
		if ( 'mla-media-assistant' === $key ) {
			return new MediaLibrary();
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		if ( class_exists( 'MLACore' ) && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id ) {
			return new MediaLibrary();
		}

		return null;
	}

}