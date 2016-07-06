<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Link extends CPAC_Storage_Model {

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
	 * @return WP_Post Post object
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

	public function get_default_columns() {

		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table = $this->get_list_table();
		$columns = (array) $table->get_columns();

		return $columns;
	}

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}