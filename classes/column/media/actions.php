<?php

/**
 * CPAC_Column_Media_Actions
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Actions extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-actions';
		$this->properties['label']	 	= __( 'Actions', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		return $this->get_column_value_actions( $id );
	}

	/**
	 * Get column value of media actions
	 *
	 * This part is copied from the Media List Table class
	 *
	 * @since 1.4.2
	 *
	 * @param int $id
	 * @return string Actions
	 */
	private function get_column_value_actions( $id ) {

		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');

		// we need class to get the object actions
		$m = new WP_Media_List_Table;

		// prevent php notice
		$m->is_trash = isset( $_REQUEST['status'] ) && 'trash' == $_REQUEST['status'];

		// get media actions
		$media 		= get_post($id);
		$actions 	= $m->_get_row_actions( $media, _draft_or_post_title($id) );

		return implode(' | ', $actions);
	}
}