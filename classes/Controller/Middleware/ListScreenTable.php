<?php

namespace AC\Controller\Middleware;

use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Sort\ManualOrder;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Table;
use AC\Type\ListScreenId;
use Exception;
use WP_Screen;
use WP_User;

class ListScreenTable implements Middleware {

	private $storage;

	private $list_screen_factory;

	private $wp_screen;

	private $preference;

	public function __construct(
		Storage $storage,
		ListScreenFactory $list_screen_factory,
		WP_Screen $wp_screen,
		Table\LayoutPreference $preference
	) {
		$this->storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
		$this->wp_screen = $wp_screen;
		$this->preference = $preference;
	}

	private function get_list_key_from_screen(): ?string {
		return $this->list_screen_factory->can_create_by_wp_screen( $this->wp_screen )
			? $this->list_screen_factory->create_by_wp_screen( $this->wp_screen )->get_key()
			: null;
	}

	private function get_first_list_screen_by_key( string $key, WP_User $user ): ?ListScreen {
		$list_screens = $this->storage->find_all_by_user( $key, $user, new ManualOrder() );

		return $list_screens->valid()
			? $list_screens->current()
			: null;
	}

	private function get_list_id( Request $request ): ?ListScreenId {
		$list_key = $this->get_list_key();

		if ( ! $list_key ) {
			return null;
		}

		$list_id = $request->get( 'layout' ) ?: $this->preference->get( $list_key );

		try {
			$list_id = new ListScreenId( (string) $list_id );
		} catch ( Exception $e ) {
			return null;
		}

		return $list_id;
	}

	private function get_list_key(): ?string {
		return $this->get_list_key_from_screen() ?: null;
	}

	private function get_list_screen( Request $request ): ?ListScreen {
		$list_key = $this->get_list_key();
		$list_id = $this->get_list_id( $request );

		if ( ! $list_key ) {
			return null;
		}

		$list_screen = null;

		if ( $list_id ) {
			$list_screen = $this->storage->find_by_user( $list_id, wp_get_current_user() );
		}

		if ( ! $list_screen ) {
			$list_screen = $this->get_first_list_screen_by_key( $list_key, wp_get_current_user() );
		}

		if ( ! $list_screen ) {
			$list_screen = $this->list_screen_factory->create( $list_key );
		}

		return $list_screen;
	}

	public function handle( Request $request ) {
		$request->get_parameters()->merge( [
			'list_screen' => $this->get_list_screen( $request ),
		] );
	}

}