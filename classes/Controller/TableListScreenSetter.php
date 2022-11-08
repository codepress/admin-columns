<?php

namespace AC\Controller;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\PermissionChecker;
use AC\Registerable;
use AC\Request;
use AC\Table;
use AC\Type\ListScreenId;
use WP_Screen;

class TableListScreenSetter implements Registerable {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var PermissionChecker
	 */
	private $permission_checker;

	/**
	 * @var Absolute
	 */
	private $location;

	/**
	 * @var Table\LayoutPreference
	 */
	private $preference;

	public function __construct( Storage $storage, PermissionChecker $permission_checker, Absolute $location, Table\LayoutPreference $preference ) {
		$this->storage = $storage;
		$this->permission_checker = $permission_checker;
		$this->location = $location;
		$this->preference = $preference;
	}

	public function register() {
		add_action( 'current_screen', [ $this, 'handle' ] );
	}

	public function handle( WP_Screen $wp_screen ) {
		$request = new Request();
		$request->add_middleware( new Middleware\ListScreenTable( $this->storage, $wp_screen, $this->preference ) );

		$list_key = $request->get( 'list_key' );

		if ( ! $list_key ) {
			return;
		}

		$list_id = $request->get( 'list_id' );

		$list_screen = ListScreenId::is_valid_id( $list_id )
			? $this->storage->find( new ListScreenId( $list_id ) )
			: null;

		if ( ! $list_screen || ! $this->permission_checker->is_valid( $list_screen ) ) {
			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
		}

		if ( ! $list_screen ) {
			return;
		}

		if ( $list_screen->has_id() ) {
			$this->preference->set( $list_screen->get_key(), $list_screen->get_id()->get_id() );
		}

		$table_screen = new Table\Screen(
			$this->location,
			$list_screen,
			new ColumnSize\ListStorage( $this->storage ),
			new ColumnSize\UserStorage( new ColumnSize\UserPreference( get_current_user_id() ) )
		);
		$table_screen->register();

		do_action( 'ac/table', $table_screen );
	}

}