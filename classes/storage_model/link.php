<?php

class CPAC_Storage_Model_Link extends CPAC_Storage_Model {

	function __construct() {

		$this->key = 'wp-links';
		$this->label = __( 'Links' );
		$this->singular_label = __( 'Link' );
		$this->type = 'link';
		$this->page = 'link-manager';

		parent::__construct();
	}

	/**
	 * @since 2.4.9
	 */
	public function init_manage_columns() {

		add_filter( "manage_{$this->page}_columns", array( $this, 'add_headings' ), 100 );
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	public function get_default_columns() {

		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table = _get_list_table( 'WP_Links_List_Table', array( 'screen' => 'link-manager' ) );
		$columns = (array) $table->get_columns();

		return $columns;
	}

	public function get_default_column_names() {
		return array();
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