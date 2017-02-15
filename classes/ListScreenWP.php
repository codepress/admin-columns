<?php

abstract class AC_ListScreenWP extends AC_ListScreen {

	/**
	 * @since NEWVERSION
	 */
	public function get_column_headers() {

		/**
         * Populate columns for get_column_headers()
		 * @see WP_List_Table::get_columns()
		 */
		$this->get_list_table();

		return (array) get_column_headers( $this->get_screen_id() );
	}

	/**
	 * Get a single row from list table
	 *
	 * @since NEWVERSION
	 */
	public function get_single_row( $object_id ) {
		ob_start();

		$this->get_list_table()->single_row( $this->get_object_by_id( $object_id ) );

		return ob_get_clean();
	}

	/**
	 * @return bool|object
	 */
	public function get_list_table() {
		return _get_list_table( $this->get_list_table_class(), array( 'screen' => $this->get_screen_id() ) );
	}

}