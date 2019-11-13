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

		$this->save( $data );
	}

	public function add( $key, $id ) {
		$data = $this->get_data();

		array_unshift( $data[ $key ], $id );

		$this->save( $data );
	}

	private function save( array $data ) {
		update_option( self::KEY, $data, false );
	}

	private function get_data() {
		return get_option( self::KEY, [] );
	}

}