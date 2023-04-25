<?php

namespace AC;

/**
 * Adapter for the WP List Table
 */
interface ListTable {

	public function get_column_value( string $column, int $id ): string;

	public function get_total_items(): int;

}