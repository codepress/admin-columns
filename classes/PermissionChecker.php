<?php

namespace AC;

use WP_User;

class PermissionChecker {

	/** @var WP_User */
	private $user;

	public function __construct( WP_User $user ) {
		$this->user = $user;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return ListScreen|null
	 */
	public function is_valid( ListScreen $list_screen ) {
		$roles = $list_screen->get_preference( 'roles' );
		$users = $list_screen->get_preference( 'users' );
		$users = is_array( $users ) ? array_map( 'intval', $users ) : [];

		if ( empty( $users ) && empty( $roles ) ) {
			return $list_screen;
		}

		if ( $roles && is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				if ( $this->user->has_cap( $role ) ) {
					return $list_screen;
				}
			}
		}

		if ( $users && in_array( $this->user->ID, $users, true ) ) {
			return $list_screen;
		}

		return null;
	}

}