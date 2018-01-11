<?php

/**
 * @since NEWVERSION
 */
abstract class AC_ListScreenWP extends AC_ListScreen {

	/**
	 * @return WP_List_Table
	 */
	abstract protected function get_list_table();

	/**
	 * @param int $id
	 *
	 * @return object
	 */
	abstract protected function get_object( $id );

	/**
	 * @param int $id
	 *
	 * @return string HTML
	 */
	public function get_single_row( $id ) {
		ob_start();
		$this->get_list_table()->single_row( $this->get_object( $id ) );

		return ob_get_clean();
	}

	/**
	 * @return array [ $column_name => [ $orderby => $order ], ... ]
	 */
	public function get_default_sortable_columns() {
		$column_info = $this->get_list_table()->get_column_info();

		if ( empty( $column_info[2] ) ) {
			return array();
		}

		return $column_info[2];
	}

	/**
	 * Get default column headers
	 *
	 * @see WP_List_Table::get_columns()
	 *
	 * @return array
	 */
	public function get_default_column_headers() {
		$this->get_list_table();

		return (array) get_column_headers( $this->get_screen_id() );
	}

}