<?php

class AC_ListScreen_Link extends AC_ListScreenWP {

	public function __construct( ) {

		$this->key = 'wp-links';
		$this->label = __( 'Links' );
		$this->singular_label = __( 'Link' );
		$this->type = 'link';
		$this->base = 'link-manager';
		$this->screen = 'link-manager';
		$this->list_table = 'WP_Links_List_Table';
		$this->group = 'link';
	}

	public function set_manage_value_callback() {
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since NEWVERSION
	 * @return stdClass
	 */
	protected function get_object_by_id( $bookmark_id ) {
		return get_bookmark( $bookmark_id );
	}

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}