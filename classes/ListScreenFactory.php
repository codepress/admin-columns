<?php

namespace AC;

use WP_Screen;

class ListScreenFactory implements ListScreenFactoryInterface {

	private static $factories = [];

	public static function add( ListScreenFactoryInterface $factory ): void {
		array_unshift( self::$factories, $factory );
	}

	public function create( string $key, array $settings = [] ): ?ListScreen {
		foreach ( self::$factories as $factory ) {
			$list_screen = $factory->create( $key, $settings );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen {
		foreach ( self::$factories as $factory ) {
			$list_screen = $factory->create_by_wp_screen( $screen, $settings );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

}