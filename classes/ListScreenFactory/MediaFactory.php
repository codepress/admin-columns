<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Media;
use AC\ListScreenFactoryInterface;
use WP_Screen;

class MediaFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	protected function create_list_screen(): Media {
		return new Media();
	}

	public function create( string $key, array $settings = [] ): ?ListScreen {
		if ( 'wp-media' === $key ) {
			return $this->add_settings( $this->create_list_screen(), $settings );
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		if ( 'edit' === $screen->base && 'attachment' === $screen->post_type ) {
			return $this->create( $screen->post_type, $settings );
		}

		return null;
	}

}