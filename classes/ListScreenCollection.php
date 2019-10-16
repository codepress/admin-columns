<?php
namespace AC;

use WP_User;

/**
 * @since NEWVERSION
 * @property ListScreen[] $items
 */
class ListScreenCollection extends Collection {

	public function filter_by_permission( WP_User $user ) {
		$list_screens = new self;

		foreach ( $this->items as $list_screen ) {
			if ( ac_user_has_permission_list_screen( $list_screen, $user ) ) {
				$list_screens->push( $list_screen );
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