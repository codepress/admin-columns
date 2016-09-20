<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Link extends AC_StorageModel {

	function init() {
		$this->key = 'wp-links';
		$this->label = __( 'Links' );
		$this->singular_label = __( 'Link' );
		$this->type = 'link';
		$this->page = 'link-manager';
		$this->screen = 'link-manager';
		$this->table_classname = 'WP_Links_List_Table';
	}

	public function init_manage_value() {
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