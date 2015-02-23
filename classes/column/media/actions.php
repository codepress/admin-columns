<?php

/**
 * CPAC_Column_Media_Actions
 *
 * @since 2.0
 */
class CPAC_Column_Media_Actions extends CPAC_Column_Actions {

	/**
	 * @see CPAC_Column_Actions::get_actions()
	 * @since 2.3.4
	 */
	public function get_actions( $id ) {

		global $wp_list_table;

		$post = get_post( $id );
		$att_title = _draft_or_post_title( $id );

		$actions = array();

		if ( $wp_list_table->detached ) {
			if ( current_user_can( 'edit_post', $post->ID ) )
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '">' . __( 'Edit' ) . '</a>';
			if ( current_user_can( 'delete_post', $post->ID ) )
				if ( EMPTY_TRASH_DAYS && MEDIA_TRASH ) {
					$actions['trash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=trash&amp;post=$post->ID", 'trash-post_' . $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				} else {
					$delete_ays = !MEDIA_TRASH ? " onclick='return showNotice.warn();'" : '';
					$actions['delete'] = "<a class='submitdelete'$delete_ays href='" . wp_nonce_url( "post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID ) . "'>" . __( 'Delete Permanently' ) . "</a>";
				}
			$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $att_title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
			if ( current_user_can( 'edit_post', $post->ID ) )
				$actions['attach'] = '<a href="#the-list" onclick="findPosts.open( \'media[]\',\''.$post->ID.'\' );return false;" class="hide-if-no-js">'.__( 'Attach' ).'</a>';
		}
		else {
			if ( current_user_can( 'edit_post', $post->ID ) && !$wp_list_table->is_trash )
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '">' . __( 'Edit' ) . '</a>';
			if ( current_user_can( 'delete_post', $post->ID ) ) {
				if ( $wp_list_table->is_trash )
					$actions['untrash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$post->ID", 'untrash-post_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
				elseif ( EMPTY_TRASH_DAYS && MEDIA_TRASH )
					$actions['trash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=trash&amp;post=$post->ID", 'trash-post_' . $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				if ( $wp_list_table->is_trash || !EMPTY_TRASH_DAYS || !MEDIA_TRASH ) {
					$delete_ays = ( !$wp_list_table->is_trash && !MEDIA_TRASH ) ? " onclick='return showNotice.warn();'" : '';
					$actions['delete'] = "<a class='submitdelete'$delete_ays href='" . wp_nonce_url( "post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID ) . "'>" . __( 'Delete Permanently' ) . "</a>";
				}
			}
			if ( !$wp_list_table->is_trash ) {
				$title =_draft_or_post_title( $post->post_parent );
				$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
			}
		}

		/**
		 * Filter the action links for each attachment in the Media list table.
		 *
		 * @since 2.8.0
		 *
		 * @param array   $actions  An array of action links for each attachment.
		 *                          Default 'Edit', 'Delete Permanently', 'View'.
		 * @param WP_Post $post     WP_Post object for the current attachment.
		 * @param bool    $detached Whether the list table contains media not attached
		 *                          to any posts. Default true.
		 */
		$actions = apply_filters( 'media_row_actions', $actions, $post, $wp_list_table->detached );

		return $actions;
	}
}
