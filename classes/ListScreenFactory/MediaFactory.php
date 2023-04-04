<?php
declare( strict_types=1 );

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Media;
use AC\ListScreenFactoryInterface;
use LogicException;
use WP_Screen;

class MediaFactory implements ListScreenFactoryInterface {

	use ListSettingsTrait;

	protected function create_list_screen(): Media {
		return new Media();
	}

	public function can_create( string $key ): bool {
		return 'wp-media' === $key;
	}

	public function can_create_by_wp_screen( WP_Screen $screen ): bool {
		return 'upload' === $screen->base && 'upload' === $screen->id && 'attachment' === $screen->post_type;
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		if ( ! $this->can_create( $key ) ) {
			throw new LogicException( 'Invalid key' );
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