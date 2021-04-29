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
	 * @return string
	 * @deprecated 3.1
	 */
	public function get_list_table_class() {
		_deprecated_function( __METHOD__, '3.1' );

		return $this->list_table_class;
	}

	/**
	 * @param string $list_table_class
	 *
	 * @deprecated 3.1
	 */
	public function set_list_table_class( $list_table_class ) {
		_deprecated_function( __METHOD__, '3.1', 'AC\ListScreenWP::get_list_table()' );

		$this->list_table_class = (string) $list_table_class;
	}

	/**
	 * @param int $id
	 *
	 * @return object
	 * @deprecated 3.1.2
	 */
	protected function get_object_by_id( $id ) {
		_deprecated_function( __METHOD__, '3.1.4', 'AC\ListScreenWP::get_object()' );

		return $this->get_object( $id );
	}

	/**
	 * @return array [ $column_name => [ $orderby, $order ], ... ]
	 */
	public function get_default_sortable_columns() {
		_deprecated_function( __METHOD__, '4.0' );

		return [];
	}

}