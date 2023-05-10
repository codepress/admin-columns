<?php
declare( strict_types=1 );

namespace AC\Admin;

use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Table\ListKeysFactoryInterface;
use AC\Type\ListKey;

class ListScreenUninitialized {

	private $default_storage;

	private $list_screen_factory;

	private $list_keys_factory;

	public function __construct(
		DefaultColumnsRepository $storage,
		ListScreenFactory $list_screen_factory,
		ListKeysFactoryInterface $list_keys_factory
	) {
		$this->default_storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
		$this->list_keys_factory = $list_keys_factory;
	}

	public function find( ListKey $list_key ): ?ListScreen {
		if ( $this->default_storage->exists( (string) $list_key ) ) {
			return null;
		}

		if ( ! $this->list_screen_factory->can_create( (string) $list_key ) ) {
			return null;
		}

		return $this->list_screen_factory->create( (string) $list_key );
	}

	public function find_all(): array {
		$list_keys = $this->list_keys_factory->create()->all();
		$list_screens = array_map( [ $this, 'find' ], $list_keys );

		return array_filter( $list_screens );
	}

}