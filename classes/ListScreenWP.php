<?php

namespace AC;

use WP_List_Table;

/**
 * @since 3.1
 */
abstract class ListScreenWP extends ListScreen {

	/**
	 * Class name of the \WP_List_Table instance
	 * @see        WP_List_Table
	 * @since      3.0
	 * @deprecated 3.1
	 * @var string
	 */
	private $list_table_class;

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
	 * @return array [ $column_name => [ $orderby, $order ], ... ]
	 */
	public function get_default_sortable_columns() {
		_deprecated_function( __METHOD__, '4.0' );

		return [];
	}

}