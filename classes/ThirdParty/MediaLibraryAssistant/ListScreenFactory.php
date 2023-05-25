<?php
declare( strict_types=1 );

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC\ListScreen;
use AC\ListScreenFactory\ListSettingsTrait;
use AC\ListScreenFactoryInterface;
use AC\ThirdParty\MediaLibraryAssistant\ListScreen\MediaLibrary;
use LogicException;
use MLACore;
use WP_Screen;

class ListScreenFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	public function can_create( string $key ): bool {
		return 'mla-media-assistant' === $key;
	}

	protected function create_list_screen(): MediaLibrary {
		return new MediaLibrary();
	}

	public function can_create_by_wp_screen( WP_Screen $screen ): bool {
		return class_exists( 'MLACore' ) && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id;
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		if ( ! $this->can_create( $key ) ) {
			throw new LogicException( 'Invalid screen' );
		}

		return $this->add_settings( $this->create_list_screen(), $settings );
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen {
		if ( ! $this->can_create_by_wp_screen( $screen ) ) {
			throw new LogicException( 'Invalid screen' );
		}

		return $this->add_settings( $this->create_list_screen(), $settings );
	}

}