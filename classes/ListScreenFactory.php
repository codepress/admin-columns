<?php
declare( strict_types=1 );

namespace AC;

use InvalidArgumentException;
use WP_Screen;

class ListScreenFactory implements ListScreenFactoryInterface {

	/**
	 * @var ListScreenFactoryInterface[]
	 */
	private static $factories = [];

	public static function add( ListScreenFactoryInterface $factory ): void {
		array_unshift( self::$factories, $factory );
	}

	public function create( string $key, array $settings = [] ): ListScreen {
		foreach ( self::$factories as $factory ) {
			if ( $factory->can_create( $key ) ) {
				return $factory->create( $key, $settings );
			}
		}

		throw new InvalidArgumentException( 'Invalid key' );
	}

	public function can_create( string $key ): bool {
		foreach ( self::$factories as $factory ) {
			if ( $factory->can_create( $key ) ) {
				return true;
			}
		}

		return false;
	}

	public function can_create_by_wp_screen( WP_Screen $screen ): bool {
		foreach ( self::$factories as $factory ) {
			if ( $factory->can_create_by_wp_screen( $screen ) ) {
				return true;
			}
		}

		return false;
	}

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen {
		foreach ( self::$factories as $factory ) {
			if ( $factory->can_create_by_wp_screen( $screen ) ) {
				return $factory->create_by_wp_screen( $screen, $settings );
			}
		}

		throw new InvalidArgumentException( 'Invalid screen' );
	}

}