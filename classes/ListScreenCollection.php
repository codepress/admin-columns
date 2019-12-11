<?php

namespace AC;

/**
 * @since 4.0
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

	/**
	 * @param ListScreen $listScreen
	 *
	 * @return bool
	 */
	public function has_list_screen( ListScreen $listScreen ) {
		/** @var ListScreen $_listScreen */
		foreach ( $this->all() as $_listScreen ) {
			if ( $_listScreen->get_layout_id() === $listScreen->get_layout_id() ) {
				return true;
			}
		}

		return false;
	}

}