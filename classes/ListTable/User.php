<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Users_List_Table;

class User implements ListTable {

	/**
	 * @var WP_Users_List_Table
	 */
	private $table;

	/**
	 * @var string
	 */
	private $column;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $value;

	public function __construct( WP_Users_List_Table $table ) {
		$this->table = $table;
	}

	public function get_column_value( $column, $id ) {
		$this->column = $column;
		$this->id = $id;

		add_filter( 'manage_users_custom_column', [ $this, 'set_value' ], 1000, 3 );

		// trigger hooks to set the value
		$this->table->single_row( get_userdata( $id ), $column );

		return $this->value;
	}

	/**
	 * @param string $value
	 * @param string $column
	 * @param int    $id
	 */
	public function set_value( $value, $column, $id ) {
		if ( '' !== $value && $column === $this->column && $id === $this->id ) {
			$this->value = $value;
		}

		return $value;
	}

}