<?php
namespace AC;

use WP_User;

// todo: remove. use FilterStrategy/ByPermission
class ListScreenPermission {

	public function user_has_permission( ListScreen $list_screen, WP_User $user ) {
		$roles = ! empty( $list_screen->get_preferences()['roles'] ) ? (array) $list_screen->get_preferences()['roles'] : [];
		$users = ! empty( $list_screen->get_preferences()['users'] ) ? (array) $list_screen->get_preferences()['users'] : [];

		if ( empty( $users ) && empty( $roles ) ) {
			return true;
		}

		foreach ( $roles as $role ) {
			if ( $user->has_cap( $role ) ) {
				return true;
			}
		}

		if ( in_array( $user->ID, $users, true ) ) {
			return true;

		}

		return false;
	}

	public function filter_by_permission( ListScreenCollection $list_screens, WP_User $user ) {
		$filered = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->user_has_permission( $list_screen, $user ) ) {
				$filered->push( $list_screen );
			}
		}

		return $filered;
	}

}