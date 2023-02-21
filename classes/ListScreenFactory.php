<?php

namespace AC;

class ListScreenFactory implements ListScreenFactoryInterface {

	private static $factories = [];

	public static function add( ListScreenFactoryInterface $factory ): void {
		self::$factories[] = $factory;
	}

	public function create( string $key, array $settings ): ?ListScreen {
		foreach ( self::$factories as $factory ) {
			$list_screen = $factory->create( $key, $settings );

			if ( $list_screen ) {
				return $list_screen;
			}
		}

		return null;
	}

}
