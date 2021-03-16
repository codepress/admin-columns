<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Terms_List_Table;

class Taxonomy implements ListTable {

	use WpListTableTrait;

	/**
	 * @var string
	 */
	private $taxonomy;

	public function __construct( WP_Terms_List_Table $table, $taxonomy ) {

		$table->get_ta

		$this->table = $table;
		$this->taxonomy = $taxonomy;
	}

	public function get_column_value( $column, $id ) {
		$this->table->get_ta
		return apply_filters( "manage_{$this->taxonomy}_custom_column", '', $column, $id );
	}

}