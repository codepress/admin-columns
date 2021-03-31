<?php

namespace AC\ListTable;

use WP_List_Table;

trait WpListTableTrait {

	/**
	 * @var WP_List_Table $table
	 */
	protected $table;

	/**
	 * @return int
	 */
	public function get_total_items() {
		return (int) $this->table->get_pagination_arg( 'total_items' );
	}

}