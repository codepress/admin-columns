<?php

namespace AC\ColumnSize;

use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class Repository {

	/**
	 * @var ListStorage
	 */
	private $list_storage;

	/**
	 * @var UserStorage
	 */
	private $user_storage;

	public function __construct( ListStorage $list_storage, UserStorage $user_storage ) {
		$this->list_storage = $list_storage;
		$this->user_storage = $user_storage;
	}

	/**
	 * @param ListScreenId $id
	 * @param string       $column_name
	 *
	 * @return ColumnWidth|null
	 */
	public function find( ListScreenId $id, $column_name ) {
		$column_width = $this->user_storage->get( $id, $column_name );

		if ( ! $column_width ) {
			$column_width = $this->list_storage->get( $id, $column_name );
		}

		return $column_width;
	}

}