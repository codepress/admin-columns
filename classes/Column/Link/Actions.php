<?php

/**
 * @since 2.0
 */
class AC_Column_Link_Actions extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-actions' );
		$this->set_label( __( 'Actions', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->get_column_value_actions( $bookmark );
	}

	/**
	 * Get column value of link actions
	 *
	 * This part is copied from the Link List Table class
	 *
	 * @since 1.4.2
	 *
	 * @param object $link
	 *
	 * @return string Actions
	 */
	public function get_column_value_actions( $link ) {
		$actions = array();

		$edit_link = get_edit_bookmark_link( $link );

		$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "link.php?action=delete&amp;link_id=$link->link_id", 'delete-bookmark_' . $link->link_id ) . "' onclick=\"if ( confirm( '" . esc_js( sprintf( __( "You are about to delete this link '%s'\n  'Cancel' to stop, 'OK' to delete." ), $link->link_name ) ) . "' ) ) { return true;}return false;\">" . __( 'Delete' ) . "</a>";

		return implode( ' | ', $actions );
	}

}