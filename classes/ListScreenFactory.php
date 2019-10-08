<?php

namespace AC;

use AC\Storage;

class ListScreenFactory {

	public function create( $key, Storage\DataObject $data = null ) {
		$list_screen = $this->get_list_screen_by_key( $key );

		if ( ! $list_screen ) {
			return false;
		}

		// todo
		// create a new reference
		$list_screen = clone $list_screen;

		if ( $data && is_array( $data->columns ) ) {
			$list_screen->set_title( $data->title );
			$list_screen->set_settings( $data->columns );
			$list_screen->set_preferences( (array) $data->settings );
			$list_screen->set_layout_id( $data->list_id );
			$list_screen->set_read_only( isset( $data->read_only ) ? $data->read_only : false );
			$list_screen->set_updated( isset( $data->date_modified ) ? $data->date_modified : 0 );
		}

		return $list_screen;
	}

	/**
	 * @param string $key
	 *
	 * @return ListScreen|false
	 */
	private function get_list_screen_by_key( $key ) {
		foreach ( ac_get_list_screen_types() as $list_screen ) {
			if ( $key === $list_screen->get_key() ) {
				return $list_screen;
			}
		}

		return false;
	}

}