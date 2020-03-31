<?php

namespace AC\Storage;

class ListScreenOrder {

	const KEY = 'ac_list_screens_order';

	public function get( $key ) {
		$orders = $this->get_data();

		if ( ! isset( $orders[ $key ] ) ) {
			return [];
		}

		return $orders[ $key ];
	}

	public function set( $key, array $list_screen_ids ) {
		$data = $this->get_data();

		$data[ $key ] = $list_screen_ids;

		update_option( self::KEY, $data, false );
	}

	public function add( $key, $id ) {
		$ids = $this->get( $key );

		array_unshift( $ids, $id );

		$this->set( $key, $ids );
	}

	private function get_data() {
		return get_option( self::KEY, [] );
	}

}