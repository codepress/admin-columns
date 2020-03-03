<?php

namespace AC;

use WP_User;

class PermissionChecker {

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return ListScreen|null
	 */
	public function is_valid( WP_User $user, ListScreen $list_screen ) {
		$roles = $list_screen->get_preference( 'roles' );
		$users = $list_screen->get_preference( 'users' );
		$users = is_array( $users ) ? array_map( 'intval', $users ) : [];

		if ( empty( $users ) && empty( $roles ) ) {
			return $list_screen;
		}

		if ( $roles && is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				if ( $user->has_cap( $role ) ) {
					return $list_screen;
				}
			}
		}

		if ( $users && in_array( $user->ID, $users, true ) ) {
			return $list_screen;
		}

		return null;
	}

}