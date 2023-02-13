<?php

namespace AC\Storage;

class ListScreenOrder {

	private const KEY = 'ac_list_screens_order';

	public function get( $key ): array {
		$orders = $this->get_data();

		return $orders[ $key ] ?? [];
	}

	public function set( $key, array $list_screen_ids ): void {
		$data = $this->get_data();

		$data[ $key ] = $list_screen_ids;

		update_option( self::KEY, $data, false );
	}

	public function add( $key, $id ): void {
		$ids = $this->get( $key );

		array_unshift( $ids, $id );

		$this->set( $key, $ids );
	}

	private function get_data(): array {
		return get_option( self::KEY, [] ) ?: [];
	}

}