<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Media extends AC_StorageModel_PostAbstract {

	public function init() {
		parent::init();

		$this->key = 'wp-media';
		$this->label = __( 'Media Library' );
		$this->singular_label = __( 'Media' );
		$this->type = 'media';
		$this->page = 'upload';
		$this->screen = 'upload';
		$this->post_type = 'attachment';
		$this->table_classname = 'WP_Media_List_Table';
	}

	public function init_manage_value() {
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	protected function get_object_by_id( $post_id ) {
		// Author column depends on this global to be set.
		global $authordata;
		$authordata = get_userdata( get_post_field( 'post_author', $post_id ) );

		return get_post( $post_id );
	}

}