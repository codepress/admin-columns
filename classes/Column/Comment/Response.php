<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 3.0
 */
class Response extends Column {

	public function __construct() {
		$this->set_type( 'response' );
		$this->set_original( true );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

	/**
	 * Response Column should not be displayed when viewing "Comments On".
	 * The list table does this by checking if $post_id is set globally. We mimic this functionality here.
	 * @see WP_Comments_List_Table::get_columns() for the WP implementation
	 */
	public function is_valid() {
		global $current_screen, $post_id;

		if ( $current_screen && $this->get_list_screen()->is_current_screen( $current_screen ) && $post_id ) {
			return false;
		}

		return true;

	}

}