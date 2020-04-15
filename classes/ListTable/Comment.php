<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Comments_List_Table;

class Comment implements ListTable {

	/**
	 * @var WP_Comments_List_Table
	 */
	private $table;

	public function __construct( WP_Comments_List_Table $table ) {
		$this->table = $table;
	}

	public function get_column_value( $column, $id ) {
		ob_start();

		$method = 'column_' . $column;

		if ( method_exists( $this->table, $method ) ) {
			call_user_func( [ $this->table, $method ], get_comment( $id ) );
		} else {
			$this->table->column_default( get_comment( $id ), $column );
		}

		return ob_get_clean();
	}

}