<?php

namespace AC;

/**
 * Adapter for the WP List Table
 */
interface ListTable {

	/**
	 * @param string $column
	 * @param int    $id
	 *
	 * @return string
	 */
	public function get_column_value( $column, $id );

}