<?php

class AC_ListScreen_Media extends AC_ListScreen_Post {

	public function __construct() {

		$this->post_type = 'attachment';
		$this->key = 'wp-media';
		$this->screen = 'upload';
		$this->type = 'media';
		$this->base = 'upload';
		$this->list_table = 'WP_Media_List_Table';
		$this->group = 'media';
	}

	public function set_manage_value_callback() {
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	protected function get_object_by_id( $post_id ) {
		// Author column depends on this global to be set.
		global $authordata;

		$authordata = get_userdata( get_post_field( 'post_author', $post_id ) );

		return get_post( $post_id );
	}

	public function get_screen_link() {
		return remove_query_arg( 'post_type', parent::get_screen_link() );
	}

}