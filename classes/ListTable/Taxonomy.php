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
		$this->table = $table;
		$this->taxonomy = (string) $taxonomy;
	}

	public function get_column_value( $column, $id ) {
		return apply_filters( "manage_{$this->taxonomy}_custom_column", '', $column, $id );
	}

}