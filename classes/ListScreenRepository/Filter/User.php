<?php
declare( strict_types=1 );

namespace AC\ListScreenRepository\Filter;

use AC\Capabilities;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use WP_User;

class User implements Filter {

	private $user;

	/**
	 * Always allow administrator to have access to the list screen
	 * @var bool
	 */
	private $allow_admin;

	public function __construct( WP_User $user, bool $allow_admin = false ) {
		$this->user = $user;
		$this->allow_admin = $allow_admin;
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		$collection = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->is_valid( $list_screen ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

	private function is_valid( ListScreen $list_screen ): bool {
		if ( $this->allow_admin && user_can( $this->user, Capabilities::MANAGE ) ) {
			return true;
		}

		$user_ids = $list_screen->get_preference( 'users' );
		$roles = $list_screen->get_preference( 'roles' );

		$user_ids = is_array( $user_ids )
			? array_map( 'intval', $user_ids )
			: [];

		if ( ! $user_ids && ! $roles ) {
			return true;
		}

		if ( $roles && is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				if ( $this->user->has_cap( $role ) ) {
					return true;
				}
			}
		}

		return $user_ids && in_array( $this->user->ID, $user_ids, true );
	}

}