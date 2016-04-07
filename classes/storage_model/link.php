<?php

class CPAC_Storage_Model_Link extends CPAC_Storage_Model {

	function __construct() {

		$this->key = 'wp-links';
		$this->label = __( 'Links' );
		$this->singular_label = __( 'Link' );
		$this->type = 'link';
		$this->page = 'link-manager';
		$this->table_classname = 'WP_Links_List_Table';

		parent::__construct();
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	public function get_meta() {
	}

	public function manage_value( $column_name, $link_id ) {

		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return false;
		}

		$value = $column->get_display_value( $link_id );

		// add hook
		$value = apply_filters( "cac/column/value", $value, $link_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $link_id, $column, $this->key );

		echo $value;
	}
}