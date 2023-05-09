<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use WP_User;

trait ListScreenPermissionTrait {

	public function user_is_assigned_to_list_screen( ListScreen $list_screen, WP_User $user ): bool {
		$user_ids = $list_screen->get_preference( 'users' );
		$roles = $list_screen->get_preference( 'roles' );

		$user_ids = is_array( $user_ids )
			? array_filter( array_map( 'intval', $user_ids ) )
			: [];

		$roles = is_array( $roles )
			? array_filter( array_map( 'strval', $roles ) )
			: [];

		if ( ! $user_ids && ! $roles ) {
			return true;
		}

		foreach ( $roles as $role ) {
			if ( $user->has_cap( $role ) ) {
				return true;
			}
		}

		return in_array( $user->ID, $user_ids, true );
	}

}