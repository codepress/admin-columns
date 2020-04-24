<?php

namespace AC;

use AC\Asset\Location\Absolute;
use AC\ListScreenRepository\Filter;
use AC\ListScreenRepository\Storage;
use AC\Table\Preference;
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

	/**
	 * @var Absolute
	 */
	private $location;

	/**
	 * @var Preference
	 */
	private $preference;

	public function __construct( Storage $storage, PermissionChecker $permission_checker, Absolute $location, Preference $preference ) {
		$this->storage = $storage;
		$this->permission_checker = $permission_checker;
		$this->location = $location;
		$this->preference = $preference;
	}

	public function register() {
		add_action( 'ac/screen', [ $this, 'init' ] );
	}

	public function init( Screen $screen ) {
		$key = $screen->get_list_screen();

		if ( ! $key ) {
			return;
		}

		// Requested
		$list_id = ListScreenId::is_valid_id( filter_input( INPUT_GET, 'layout' ) )
			? new ListScreenId( filter_input( INPUT_GET, 'layout' ) )
			: null;

		// Last visited
		if ( ! $list_id ) {
			$list_id_preference = $this->preference->get( $key );
			$list_id = ListScreenId::is_valid_id( $list_id_preference )
				? new ListScreenId( $list_id_preference )
				: null;
		}

		$list_screen = null;

		if ( $list_id ) {
			$requested_list_screen = $this->storage->find( $list_id );

			if ( $requested_list_screen && $requested_list_screen->get_key() === $key && $this->permission_checker->is_valid( wp_get_current_user(), $requested_list_screen ) ) {
				$list_screen = $requested_list_screen;
			}
		}

		// First visit or not found
		if ( ! $list_screen ) {
			$list_screen = $this->get_first_list_screen( $key );
		}

		if ( ! $list_screen ) {
			return;
		}

		if ( $list_screen->has_id() ) {
			$this->preference->set( $key, $list_screen->get_id()->get_id() );
		}

		$table_screen = new Table\Screen( $this->location, $list_screen );
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