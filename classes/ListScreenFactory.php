<?php

namespace AC;

use WP_Screen;

class ListScreenFactory implements ListScreenFactoryInterface {

	private static $factories = [];

	public static function add( ListScreenFactoryInterface $factory, int $priority = 10 ): void {
		self::$factories[ $priority ][] = $factory;
	}

	/**
	 * @return ListScreenFactoryInterface[]
	 */
	public function all(): array {
		$factories = self::$factories;

		ksort( $factories );

		return array_merge( ...$factories );
	}

	public function create( string $key, array $settings = [] ): ?ListScreen {
		foreach ( $this->all() as $factory ) {
			$list_screen = $factory->create( $key, $settings );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		foreach ( $this->all() as $factory ) {
			$list_screen = $factory->create_by_wp_screen( $screen, $settings );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

}
