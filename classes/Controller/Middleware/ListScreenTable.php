<?php

namespace AC\Controller\Middleware;

use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Middleware;
use AC\Request;
use AC\Screen;
use AC\Table;
use AC\Type\ListScreenId;
use WP_Screen;
use WP_User;

class ListScreenTable implements Middleware {

	private const PARAM_LIST_ID = 'list_id';
	private const PARAM_LIST_KEY = 'list_key';

	private $storage;

	private $wp_screen;

	private $preference;

	public function __construct( Storage $storage, WP_Screen $wp_screen, Table\LayoutPreference $preference ) {
		$this->storage = $storage;
		$this->wp_screen = $wp_screen;
		$this->preference = $preference;
	}

	private function get_list_key_from_screen(): ?string {
		return ( new Screen() )->set_screen( $this->wp_screen )->get_list_screen();
	}

	private function get_first_list_screen_by_key( string $key, WP_User $user ): ?ListScreen {
		$list_screens = $this->storage->find_all_by_user( $key, $user, 'manual' );

		return $list_screens->get_first() ?: null;
	}

	public function handle( Request $request ) {
		$user = wp_get_current_user();

		if ( ! $user ) {
			return;
		}

		$list_key = $request->get( 'list_key' ) ?: $this->get_list_key_from_screen();

		if ( ! $list_key || ! is_string( $list_key ) ) {
			return;
		}

		$list_id = $request->get( 'layout' ) ?: $this->preference->get( $list_key );

		$list_screen = null;

		if ( ListScreenId::is_valid_id( $list_id ) ) {
			$list_screen = $this->storage->find_by_user( new ListScreenId( $list_id ), $user );
		}

		if ( ! $list_screen ) {
			$list_screen = $this->get_first_list_screen_by_key( $list_key, $user );
		}

		if ( ! $list_screen ) {
			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
		}

		$request->get_parameters()->merge( [
			self::PARAM_LIST_KEY => $list_screen ? $list_screen->get_key() : null,
			self::PARAM_LIST_ID  => $list_screen && $list_screen->has_id() ? (string) $list_screen->get_id() : null,
		] );
	}

}