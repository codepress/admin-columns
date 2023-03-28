<?php

namespace AC\Controller;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Table;
use WP_Screen;

class TableListScreenSetter implements Registerable {

	private $storage;

	private $location;

	private $list_screen_factory;

	private $preference;

	public function __construct(
		Storage $storage,
		Absolute $location,
		ListScreenFactory $list_screen_factory,
		Table\LayoutPreference $preference
	) {
		$this->storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
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
				$this->list_screen_factory,
				$wp_screen,
				$this->preference
			)
		);

		$list_screen = $request->get( 'list_screen' );

		if ( ! $list_screen instanceof ListScreen ) {
			return;
		}

		if ( $list_screen->has_id() ) {
			$this->preference->set( $list_screen->get_key(), (string) $list_screen->get_id() );
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