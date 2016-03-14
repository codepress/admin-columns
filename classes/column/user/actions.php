<?php

/**
 * CPAC_Column_User_Actions
 *
 * @since 2.0
 */
class CPAC_Column_User_Actions extends CPAC_Column_Actions {

	/**
	 * @see CPAC_Column_Actions::get_actions()
	 * @since 2.4.7
	 */
	public function get_actions( $id ) {
		$actions = array();

		$user_object = new WP_User( $id );
		$screen = get_current_screen();

		if ( 'site-users-network' == $screen->id ) {
			$url = "site-users.php?id={$this->site_id}&amp;";
		}
		else {
			$url = 'users.php?';
		}

		if ( get_current_user_id() == $user_object->ID ) {
			$edit_link = 'profile.php';
		}
		else {
			$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( stripslashes( $_SERVER['REQUEST_URI'] ) ), "user-edit.php?user_id=$user_object->ID" ) );
		}

		if ( current_user_can( 'edit_user', $user_object->ID ) ) {
			$edit = "<strong><a href=\"$edit_link\">$user_object->user_login</a></strong><br />";
			$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		}
		else {
			$edit = "<strong>$user_object->user_login</strong><br />";
		}

		if ( ! is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) ) {
			$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . "</a>";
		}
		if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) ) {
			$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url . "action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . "</a>";
		}

		return $actions;
	}

}