<?php

/**
 * CPAC_Column_Media_Actions
 *
 * @since 2.0
 */
class CPAC_Column_Media_Actions extends CPAC_Column_Actions {

	/**
	 * @see CPAC_Column_Actions::get_actions()
	 * @since NEWVERSION
	 */
	public function get_actions( $item_id ) {

		global $wp_list_table;

		return $wp_list_table->_get_row_actions( get_post( $item_id ), _draft_or_post_title( $item_id ) );
		
		$att_title = _draft_or_post_title();


		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') ) {
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		}
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') ) {
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');
		}

		// we need class to get the object actions
		$m = new WP_Media_List_Table;

		// prevent php notice
		$m->is_trash = isset( $_REQUEST['status'] ) && 'trash' == $_REQUEST['status'];

		// get media actions
		$media 		= get_post( $item_id );
		$actions 	= $m->_get_row_actions( $media, _draft_or_post_title( $item_id ) );

		return implode( ' | ', $actions );
	}
}
