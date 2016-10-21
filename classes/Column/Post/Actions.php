<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Actions extends AC_Column_ActionsAbstract {

	public function get_actions( $id ) {
		$table = new AC_WP_Posts_List_Table();

		return $table->get_handle_row_actions( get_post( $id ), $this->get_name() );
	}

}

// Include
require_once ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php';

/**
 * Make the protected method 'handle_row_actions' accessible
 */
class AC_WP_Posts_List_Table extends WP_Posts_List_Table {

	public function get_handle_row_actions( $post, $column ) {
		return $this->handle_row_actions( $post, $column, $column );
	}

}