<?php
/**
 * CPAC_Column_Post_Actions
 *
 * @since 2.0
 */
class CPAC_Column_Post_Actions extends CPAC_Column_Actions {

	/**
	 * @see CPAC_Column_Actions::get_actions()
	 * @since 2.2.6
	 */
	public function get_actions( $item_id ) {

		$actions = array();

		$post 				= get_post( $item_id );
		$title 				= _draft_or_post_title();
		$post_type_object 	= get_post_type_object( $post->post_type );
		$can_edit_post 		= current_user_can( $post_type_object->cap->edit_post, $post->ID );
		$quickedit_enabled	= false;

		$stored_columns = $this->storage_model->get_stored_columns();

		foreach ( $stored_columns as $column ) {
			if ( $column['type'] == 'title' ) {
				$quickedit_enabled = true;
			}
		}

		if ( $can_edit_post && 'trash' != $post->post_status ) {
			$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';

			if ( $quickedit_enabled ) {
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
		}
		if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
			if ( 'trash' == $post->post_status )
				$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-post_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
			elseif ( EMPTY_TRASH_DAYS )
				$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
			if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
				$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
		}
		if ( $post_type_object->public ) {
			if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
				if ( $can_edit_post )
					$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
			} elseif ( 'trash' != $post->post_status ) {
				$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
			}
		}

		return $actions;
	}

}