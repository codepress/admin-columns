<?php

namespace AC;

use AC\Type\ListScreenId;

class ListscreenStateRepository {

	const KEY = 'ac_list_screen_disabled';

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function is_disabled( ListScreenId $id ) {
		$ids = $this->get_option();

		return in_array( $id->get_id(), $ids );
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function add( ListScreenId $id ) {
		$ids = $this->get_option();

		if ( in_array( $id->get_id(), $ids ) ) {
			return false;
		}

		$ids[] = $id->get_id();

		$this->save_option( $ids );

		return true;
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function delete( ListScreenId $id ) {
		$ids = $this->get_option();

		$key = array_search( $id->get_id(), $ids );

		if ( false === $key ) {
			return false;
		}

		unset( $ids[ $key ] );

		if ( empty( $ids ) ) {
			$this->delete_all();
		}

		$this->save_option( $ids );

		return true;
	}

	private function save_option( array $ids ) {
		update_option( self::KEY, $ids, false );
	}

	/**
	 * @return array
	 */
	private function get_option() {
		return (array) get_option( self::KEY, [] );
	}

	private function delete_all() {
		delete_option( self::KEY );
	}

}