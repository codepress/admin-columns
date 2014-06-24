<?php

class CPAC_Storage_Model_Link extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0
	 */
	function __construct() {

		$this->key 		= 'wp-links';
		$this->label 	= __( 'Links' );
		$this->type 	= 'link';
		$this->page 	= 'link-manager';
		$this->menu_type = 'other';

		// headings
		add_filter( "manage_{$this->page}_columns",  array( $this, 'add_headings' ), 100 );

		// values
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 100, 2 );

		parent::__construct();
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		if ( ! function_exists('_get_list_table') ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table 		= _get_list_table( 'WP_Links_List_Table', array( 'screen' => 'link-manager' ) );
		$columns 	= (array) $table->get_columns();

		return $columns;
	}

	/**
     * Get Meta
     *
	 * @since 2.0
	 *
	 * @return array
     */
    public function get_meta() {}

	/**
	 * Manage value
	 *
	 * @since 2.0
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $column_name, $link_id ) {

		$value = '';

		// get column instance
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $link_id );
		}

		// add hook
		$value = apply_filters( "cac/column/value", $value, $link_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $link_id, $column, $this->key );

		echo $value;
	}

}