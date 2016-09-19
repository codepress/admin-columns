<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Link extends AC_StorageModel {

	function init() {
		$this->key = 'wp-links';
		$this->label = __( 'Links' );
		$this->singular_label = __( 'Link' );
		$this->type = 'link';
		$this->page = 'link-manager';
		$this->table_classname = 'WP_Links_List_Table';
	}

	/**
	 * @since NEWVERSION
	 * @return stdClass
	 */
	protected function get_object_by_id( $id ) {
		return get_bookmark( $id );
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}