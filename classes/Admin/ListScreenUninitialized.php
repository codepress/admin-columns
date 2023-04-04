<?php
declare( strict_types=1 );

namespace AC\Admin;

use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\ListScreenFactoryInterface;

class ListScreenUninitialized {

	private $default_storage;

	private $list_screen_factory;

	public function __construct( DefaultColumnsRepository $storage, ListScreenFactoryInterface $list_screen_factory ) {
		$this->default_storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
	}

	public function find( string $list_key ): ?ListScreen {
		if ( $this->default_storage->exists( $list_key ) ) {
			return null;
		}

		if ( ! $this->list_screen_factory->can_create( $list_key ) ) {
			return null;
		}

		return $this->list_screen_factory->create( $list_key );
	}

	public function find_all( array $list_keys ): array {
		$list_screens = array_map( [ $this, 'find' ], $list_keys );

		return array_filter( $list_screens );
	}

}