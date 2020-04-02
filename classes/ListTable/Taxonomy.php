<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Terms_List_Table;

class Taxonomy implements ListTable {

	/**
	 * @var WP_Terms_List_Table
	 */
	private $table;

	/**
	 * @var string
	 */
	private $taxonomy;

	public function __construct( WP_Terms_List_Table $table, $taxonomy ) {
		$this->table = $table;
		$this->taxonomy = $taxonomy;
	}

	public function get_column_value( $column, $id ) {
		return $this->table->column_default( get_term_by( 'id', $id, $this->taxonomy ), $column );
	}

}