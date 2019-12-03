<?php

namespace AC\ListScreenRepository\FilterStrategy;

use AC\ListScreen;
use AC\ListScreenRepository\FilterStrategy;
use WP_User;

// todo: wip. implement in Repo
class ByPermission implements FilterStrategy {

	/** @var WP_User */
	protected $user;

	public function __construct( WP_User $user ) {
		$this->user = $user;
	}

	/**
	 * @param ListScreen $list_screen
	 * @param WP_User    $user
	 *
	 * @return ListScreen|null
	 */
	public function filter( ListScreen $list_screen ) {
		$roles = ! empty( $list_screen->get_preferences()['roles'] ) ? (array) $list_screen->get_preferences()['roles'] : [];
		$users = ! empty( $list_screen->get_preferences()['users'] ) ? (array) $list_screen->get_preferences()['users'] : [];

		if ( empty( $users ) && empty( $roles ) ) {
			return $list_screen;
		}

		foreach ( $roles as $role ) {
			if ( $this->user->has_cap( $role ) ) {
				return $list_screen;
			}
		}

		if ( in_array( $this->user->ID, $users, true ) ) {
			return $list_screen;

		}

		return null;
	}

}