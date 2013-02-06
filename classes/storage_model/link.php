<?php

class CPAC_Storage_Model_Link extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {		
		
		$this->key 		= 'wp-links';
		$this->label 	= __( 'Links' );
		$this->type 	= 'link';
		
		// headings
		add_filter( "manage_link-manager_columns",  array( $this, 'add_headings' ) );
		
		// values
		add_action( 'manage_link_custom_column', array( $this, 'manage_value' ), 10, 2 );
		
		parent::__construct();
	}
	
	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_default_columns() {
		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cpac_before_default_columns_{$this->key}" );
		
		// dependencies
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php' );

		// As of WP Release 3.5 we can use the following.
		$table 		= new WP_Links_List_Table( array( 'screen' => 'link-manager' ) );
		$columns 	= $table->get_columns();

		if ( empty( $columns ) )
			return false;
		
		return $columns;
	}
	
	/**
	 * Manage value
	 *
	 * @since 2.0.0
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
		echo apply_filters( "cpac_value_{$this->key}", $value, $column );
	}
	
}