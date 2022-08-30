<?php

namespace AC\ColumnRepository\Sort;

use AC\ColumnRepository\Sort;
use AC\Storage;
use AC\Type\ListScreenId;

class ManualOrder implements Sort {

	/**
	 * @var ListScreenId
	 */
	private $list_id;

	/**
	 * @var Storage\UserColumnOrder
	 */
	private $user_order;

	public function __construct( ListScreenId $list_id, Storage\UserColumnOrder $user_order ) {
		$this->list_id = $list_id;
		$this->user_order = $user_order;
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public function sort( array $columns ) {
		if ( ! $this->user_order->exists( $this->list_id ) ) {
			return $columns;
		}

		$ordered = [];

		foreach ( $this->user_order->get( $this->list_id ) as $column_name ) {
			if ( ! isset( $columns[ $column_name ] ) ) {
				continue;
			}

			$ordered[ $column_name ] = $columns[ $column_name ];

			unset( $columns[ $column_name ] );
		}

		return array_merge( $ordered, $columns );
	}

}