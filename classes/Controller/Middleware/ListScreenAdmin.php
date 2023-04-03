<?php

namespace AC\Controller\Middleware;

use AC;
use AC\Admin\Preference;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Type\ListScreenId;
use Exception;

class ListScreenAdmin implements Middleware {

	private $storage;

	private $preference;

	private $list_screen_factory;

	private $supported_list_keys;

	public function __construct(
		Storage $storage,
		Preference\ListScreen $preference,
		ListScreenFactory $list_screen_factory,
		array $supported_list_keys
	) {
		$this->storage = $storage;
		$this->preference = $preference;
		$this->list_screen_factory = $list_screen_factory;
		$this->supported_list_keys = $supported_list_keys;
	}

	private function get_list_id( Request $request ): ?ListScreenId {
		try {
			$list_id = new ListScreenId( (string) $request->get( 'layout_id' ) );
		} catch ( Exception $e ) {
			return null;
		}

		return $list_id;
	}

	private function get_list_key( Request $request ): ?string {
		$list_key = (string) $request->get( 'list_screen' );

		if ( ! $this->list_key_exists( $list_key ) ) {
			$list_key = $this->preference->get_last_visited_list_key();
		}

		if ( ! $this->list_key_exists( $list_key ) ) {
			$list_key = $this->supported_list_keys[0];
		}

		return $this->list_key_exists( $list_key )
			? $list_key
			: null;
	}

	private function list_key_exists( string $list_key ): bool {
		return $this->list_screen_factory->can_create( $list_key );
	}

	private function get_list_screen( Request $request ): ?ListScreen {
		$list_id = $this->get_list_id( $request );

		if ( $list_id && $this->storage->exists( $list_id ) ) {
			return $this->storage->find( $list_id );
		}

		$list_key = $this->get_list_key( $request );

		if ( ! $list_key ) {
			return null;
		}

		$list_id = $this->get_last_visited_list_id( $list_key );

		if ( $list_id && $this->storage->exists( $list_id ) ) {
			return $this->storage->find( $list_id );
		}

		$list_screens = $this->storage->find_all_by_key( $list_key );

		if ( $list_screens->count() > 0 ) {
			return $list_screens->current();
		}

		if ( ! $this->list_screen_factory->can_create( $list_key ) ) {
			return null;
		}

		return $this->list_screen_factory->create( $list_key );
	}

	private function get_last_visited_list_id( string $list_key ): ?ListScreenId {
		try {
			$list_id = new ListScreenId( (string) $this->preference->get_list_id( $list_key ) );
		} catch ( Exception $e ) {
			return null;
		}

		return $list_id;
	}

	private function set_preference_screen( ListScreen $list_screen ): void {
		$this->preference->set_last_visited_list_key( $list_screen->get_key() );

		if ( $list_screen->has_id() ) {
			$this->preference->set_list_id( $list_screen->get_key(), $list_screen->get_id()->get_id() );
		}
	}

	public function handle( Request $request ) {
		$list_screen = $this->get_list_screen( $request );

		if ( ! $list_screen ) {
			return;
		}

		$this->set_preference_screen( $list_screen );

		$request->get_parameters()->merge( [
			'list_screen' => $list_screen,
		] );
	}

}