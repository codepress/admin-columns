<?php
defined( 'ABSPATH' ) or die();

abstract class AC_WPStorageModel extends AC_StorageModel {

	/**
	 * @since NEWVERSION
	 */
	public function get_default_columns() {
		if ( ! function_exists( 'get_column_headers' ) ) {
			return array();
		}
		// trigger WP_List_Table::get_columns()
		$this->get_list_table();

		return (array) get_column_headers( $this->get_screen_id() );
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return WP_List_Table|false
	 */
	public function get_list_table( $args = array() ) {
		return function_exists( '_get_list_table' ) ? _get_list_table( $this->table_classname, array( 'screen' => $this->get_screen_id() ) ) : false;
	}


}