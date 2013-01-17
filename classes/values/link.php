<?php

/**
 * CPAC_Link_Values Class
 *
 * @since 1.4.4
 *
 */
class CPAC_Link_Values extends CPAC_Values {

	/**
	 * Constructor
	 *
	 * @since 1.4.4
	 */
	function __construct() {
		parent::__construct();

		add_action( 'manage_link_custom_column', array( $this, 'manage_link_column_value' ), 10, 2 );
	}

	/**
	 * Manage custom column for Links
	 *
	 * @since 1.3.1
	 *
	 * @param string $column_name
	 * @param int $link_id
	 * @return string Value
	 */
	public function manage_link_column_value( $column_name, $link_id ) {

		// links object... called bookmark
		$bookmark = get_bookmark( $link_id );

		// set column value
		switch ( $column_name ) :

			case "column-link_id" :
				$result = $link_id;
				break;

			case "column-description" :
				$result = $bookmark->link_description;
				break;

			case "column-target" :
				$result = $bookmark->link_target;
				break;

			case "column-notes" :
				$result = $this->get_shortened_string( $bookmark->link_notes, $this->excerpt_length );
				break;

			case "column-rss" :
				$result = $this->get_shorten_url( $bookmark->link_rss );
				break;

			case "column-image" :
				$result = $this->get_thumbnails( $bookmark->link_image );
				break;

			case "column-length" :
				$result = strlen( $bookmark->link_name );
				break;

			case "column-owner" :
				$result = $bookmark->link_owner;

				// add user link
				$userdata = get_userdata( $bookmark->link_owner );
				if ( ! empty( $userdata->data ) ) {
					$result = $userdata->data->user_nicename;
					//$result = "<a href='user-edit.php?user_id={$bookmark->link_owner}'>{$result}</a>";
				}
				break;

			case "column-actions" :
				$result = $this->get_column_value_actions( $bookmark );
				break;

			default :
				$result = '';

		endswitch;

		// Filter for customizing the result output
		echo apply_filters( "cpac_{$this->storage_key}_column_value", $result, $column_name, $column_name, $link_id );
	}

	/**
	 * Get column value of link actions
	 *
	 * This part is copied from the Link List Table class
	 *
	 * @since 1.4.2
	 *
	 * @param object $link
	 * @return string Actions
	 */
	private function get_column_value_actions( $link ) {
		$actions = array();

		$edit_link = get_edit_bookmark_link( $link );

		$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "link.php?action=delete&amp;link_id=$link->link_id", 'delete-bookmark_' . $link->link_id ) . "' onclick=\"if ( confirm( '" . esc_js( sprintf( __( "You are about to delete this link '%s'\n  'Cancel' to stop, 'OK' to delete." ), $link->link_name ) ) . "' ) ) { return true;}return false;\">" . __( 'Delete' ) . "</a>";

		return implode( ' | ', $actions );
	}
}