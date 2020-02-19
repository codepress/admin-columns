<?php

namespace AC;

use AC\ListScreenRepository\Filter;
use AC\ListScreenRepository\Storage;
use AC\Type\ListScreenId;

class TableLoader implements Registrable {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var PermissionChecker
	 */
	private $permission_checker;

	public function __construct( Storage $storage, PermissionChecker $permission_checker ) {
		$this->storage = $storage;
		$this->permission_checker = $permission_checker;
	}

	public function register() {
		add_action( 'ac/screen', [ $this, 'init' ] );
	}

	private function preferences() {
		return new Preferences\Site( 'layout_table' );
	}

	public function init( Screen $screen ) {
		$key = $screen->get_list_screen();

		if ( ! $key ) {
			return;
		}

		// Requested
		$list_id = filter_input( INPUT_GET, 'layout' );

		// Last visited
		if ( ! $list_id ) {
			$list_id = $this->preferences()->get( $key );
		}

		$list_screen = null;

		if ( $list_id ) {
			$requested_list_screen = $this->storage->find( new ListScreenId( $list_id ) );

			$user = wp_get_current_user();

			if ( $user && $requested_list_screen && $this->permission_checker->is_valid( $user, $requested_list_screen ) ) {
				$list_screen = $requested_list_screen;
			}
		}

		// First visit or not found
		if ( ! $list_screen ) {
			$list_screen = $this->get_first_list_screen( $key );
		}

		if ( $list_screen->get_layout_id() ) {
			$this->preferences()->set( $key, new ListScreenId( $list_screen->get_layout_id() ) );
		}

		$table_screen = new Table\Screen( $list_screen );
		$table_screen->register();

		do_action( 'ac/table', $table_screen );
	}

	/**
	 * @param string $key
	 *
	 * @return ListScreen|null
	 */
	private function get_first_list_screen( $key ) {
		$list_screens = $this->storage->find_all( [
			'key'    => $key,
			'filter' => new Filter\Permission( $this->permission_checker ),
		] );

		if ( $list_screens->count() > 0 ) {

			// First visit. Load first available list Id.
			return $list_screens->get_first();
		}

		// No available list screen found.
		return ListScreenTypes::instance()->get_list_screen_by_key( $key );
	}

}