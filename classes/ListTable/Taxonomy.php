<?php

namespace AC\ListTable;

use AC\ListTable;

class Taxonomy implements ListTable {

	/**
	 * @var string
	 */
	private $taxonomy;

	public function __construct( $taxonomy ) {
		$this->taxonomy = $taxonomy;
	}

	public function get_column_value( $column, $id ) {
		return apply_filters( "manage_{$this->taxonomy}_custom_column", '', $column, $id );
	}

}