<?php

class AC_ListScreen_Media extends AC_ListScreenPost {

	public function __construct() {
		parent::__construct( 'attachment' );

		$this->set_screen_id( 'upload' );
		$this->set_screen_base( 'upload' );
		$this->set_key( 'wp-media' );
		$this->set_group( 'media' );
		$this->set_label( __( 'Media' ) );

		/* @see WP_Media_List_Table */
		$this->set_list_table_class( 'WP_Media_List_Table' );
	}

	public function set_manage_value_callback() {
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	protected function get_object_by_id( $post_id ) {
		// Author column depends on this global to be set.
		global $authordata;

		$authordata = get_userdata( get_post_field( 'post_author', $post_id ) );

		return parent::get_object_by_id( $post_id );
	}

	/**
	 * @since 2.4.7
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}