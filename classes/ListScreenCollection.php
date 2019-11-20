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
	 * Removes duplicate list screens (with the same ID) based on its `red only` state and `updated` timestamp
	 * @return ListScreenCollection
	 */
	public function filter_unique() {
		$list_screens = new self();

		/** @var ListScreen $list_screen */
		foreach ( $this->all() as $list_screen ) {

			if ( $list_screens->has( $list_screen->get_layout_id() ) ) {

				/** @var ListScreen $_list_screen */
				$_list_screen = $list_screens->get( $list_screen->get_layout_id() );

				if ( $_list_screen->is_read_only() ) {
					continue;
				}

				if ( $_list_screen->get_updated() > $list_screen->get_updated() ) {
					continue;
				}
			}

			$list_screens->put( $list_screen->get_layout_id(), $list_screen );
		}

		return $list_screens;
	}

}