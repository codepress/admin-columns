<?php

namespace AC\ListScreenRepository;

use AC\Capabilities;
use AC\ListScreen;
use WP_User;

trait ListScreenPermissionTrait {

	public function user_can_view_list_screen( ListScreen $list_screen, WP_User $user ): bool {
		if ( user_can( $user, Capabilities::MANAGE ) ) {
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
				if ( $user->has_cap( $role ) ) {
					return true;
				}
			}
		}

		return $user_ids && in_array( $user->ID, $user_ids, true );
	}

}