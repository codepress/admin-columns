<?php

namespace AC\Controller;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Registerable;
use AC\Request;
use AC\Table;
use AC\Type\ListScreenId;
use WP_Screen;

class TableListScreenSetter implements Registerable {

	private $storage;

	private $location;

	private $preference;

	public function __construct(
		Storage $storage,
		Absolute $location,
		Table\LayoutPreference $preference
	) {
		$this->storage = $storage;
		$this->location = $location;
		$this->preference = $preference;
	}

	public function register() {
		add_action( 'current_screen', [ $this, 'handle' ] );
	}

	public function handle( WP_Screen $wp_screen ): void {
		$request = new Request();
		$request->add_middleware(
			new Middleware\ListScreenTable(
				$this->storage,
				$wp_screen,
				$this->preference
			)
		);

		$list_key = $request->get( 'list_key' );
		$list_id = $request->get( 'list_id' );

		$list_screen = null;

		if ( ListScreenId::is_valid_id( $list_id ) ) {
			$list_screen = $this->storage->find( new ListScreenId( $list_id ) );
		}

		if ( ! $list_screen && $list_key && is_string( $list_key ) ) {
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