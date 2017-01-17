<?php

abstract class AC_ListScreenWP extends AC_ListScreen {

	/**
	 * @since NEWVERSION
	 */
	public function get_default_columns() {
		if ( ! function_exists( 'get_column_headers' ) ) {
			return array();
		}
		// trigger WP_List_Table::get_columns()
		$this->get_list_table();

		// TODO
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
	 * @since NEWVERSION
	 *
	 * @return WP_List_Table|false
	 */
	public function get_list_table( $args = array() ) {
		return function_exists( '_get_list_table' ) ? _get_list_table( $this->list_table, array( 'screen' => $this->get_screen_id() ) ) : false;
	}

}