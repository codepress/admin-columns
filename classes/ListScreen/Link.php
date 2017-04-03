<?php

class AC_ListScreen_Link extends AC_ListScreen {

	public function __construct() {

		$this->set_label( __( 'Links' ) );
		$this->set_singular_label( __( 'Link' ) );
		$this->set_screen_base( 'link-manager' );
		$this->set_key( 'wp-links' );
		$this->set_screen_id( 'link-manager' );
		$this->set_group( 'link' );

		/* @see WP_Links_List_Table */
		$this->set_list_table_class( 'WP_Links_List_Table' );
	}

	public function set_manage_value_callback() {
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since 3.0
	 * @return stdClass
	 */
	protected function get_object_by_id( $bookmark_id ) {
		return get_bookmark( $bookmark_id );
	}

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}