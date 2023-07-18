<?php

namespace AC;

use WP_List_Table;

// TODO remove
/**
 * @since 3.1
 */
abstract class ListScreenWP extends ListScreen {

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

}