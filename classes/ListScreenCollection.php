<?php
namespace AC;

use WP_User;

/**
 * @since NEWVERSION
 * @property ListScreen[] $items
 */
class ListScreenCollection extends Collection {

	public function add_collection( ListScreenCollection $collection ) {
		if ( ! $collection->count() ) {
			return;
		}

		foreach ( $collection as $item ) {
			$this->push( $item );
		}
	}

	public function filter_by_permission( WP_User $user ) {
		$list_screens = new self;

		foreach ( $this->items as $list_screen ) {
			if ( ac_user_has_permission_list_screen( $list_screen, $user ) ) {
				$list_screens->push( $list_screen );
			}
		}

		return $list_screens;
	}

	/**
	 * @param ListScreen $listScreen
	 *
	 * @return bool
	 */
	public function hasListScreen( ListScreen $listScreen ) {
		/** @var ListScreen $_listScreen
		 */
		foreach ( $this->all() as $_listScreen ) {
			if ( $_listScreen->get_layout_id() === $listScreen->get_layout_id() ) {
				return true;
			}
		}

		return false;
	}

}