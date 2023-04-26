<?php

namespace AC;

trait ListTableTrait {

	private function get_list_table(): ListTable {
		static $list_table = null;

		if ( null === $list_table ) {
			$list_table = ( new ListTableFactory() )->create_from_globals();
		}

		return $list_table;
	}

	public function get_total_items(): int {
		return $this->get_list_table()->get_total_items();
	}

	public function get_column_value_original( string $column_name, int $id ): string {
		return $this->get_list_table()->get_column_value( $column_name, $id );
	}

}