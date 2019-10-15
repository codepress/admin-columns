<?php
namespace AC;

use WP_User;

/**
 * @since NEWVERSION
 * @property ListScreen[] $items
 */
class ListScreenCollection extends Collection {

	public function filter_by_user( $user_id ) {
		$list_screens = [];

		foreach ( $this->items as $list_screen ) {

			// todo
			$user_ids = $list_screen->get_preferences()['users'];

			if ( ! $user_ids || in_array( $user_id, $user_ids, true ) ) {
				$list_screens[] = $list_screen;
			}
		}

		return new self( $list_screens );
	}

	public function filter_by_role( $role ) {
		$list_screens = [];

		foreach ( $this->items as $list_screen ) {
			//$roles = $list_screen->get_settings()->get( 'roles' );
			$roles = [];

			if ( ! $roles || in_array( $role, $roles, true ) ) {
				$list_screens[] = $list_screen;
			}
		}

		return new self( $list_screens );
	}

	public function filter_by_permission( WP_User $user ) {
		$list_screens = new self;

		foreach ( $this->items as $list_screen ) {
			$roles = ! empty( $list_screen->get_preferences()['roles'] ) ? $list_screen->get_preferences()['roles'] : [];
			$users = ! empty( $list_screen->get_preferences()['users'] ) ? $list_screen->get_preferences()['users'] : [];

			foreach ( $roles as $role ) {
				if ( $user->has_cap( $role ) ) {
					$list_screens->push( $list_screen );
					continue;
				}
			}

			if ( in_array( $user->ID, $users, true ) ) {
				$list_screens->push( $list_screen );
				continue;

			}

			if ( empty( $users ) && empty( $roles ) ) {
				$list_screens->push( $list_screen );
				continue;
			}

		}

		return $list_screens;
	}

	public function add_collection( ListScreenCollection $collection ) {
		if ( ! $collection->count() ) {
			return;
		}

		foreach ( $collection as $item ) {
			$this->push( $item );
		}
	}

}