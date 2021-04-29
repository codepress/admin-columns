<?php

namespace AC;

use WP_User;

class PermissionChecker {

	/**
	 * @param ListScreen   $list_screen
	 * @param WP_User|null $user
	 *
	 * @return bool
	 */
	public function is_valid( ListScreen $list_screen, WP_User $user = null ) {
		if ( null === $user ) {
			$user = wp_get_current_user();
		}

		$users = $list_screen->get_preference( 'users' );
		$roles = $list_screen->get_preference( 'roles' );

		$users = is_array( $users )
			? array_map( 'intval', $users )
			: [];

		if ( empty( $users ) && empty( $roles ) ) {
			return true;
		}

		if ( $roles && is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				if ( $user->has_cap( $role ) ) {
					return true;
				}
			}
		}

		return $users && in_array( $user->ID, $users, true );
	}

}