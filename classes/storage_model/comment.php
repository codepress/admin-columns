<?php

class CPAC_Storage_Model_Comment extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {		
		
		$this->key 		= 'wp-comments';
		$this->label 	= __( 'Comments' );
		$this->type 	= 'comment';
		
		// headings
		add_filter( "manage_edit-comments_columns", array( $this, 'add_headings' ) );
		
		// values
		add_action( 'manage_comments_custom_column', array( $this, 'manage_value' ), 10, 2 );
		
		parent::__construct();
	}
	
	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
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
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );

		// As of WP Release 3.5 we can use the following.
		$table 		= new WP_Comments_List_Table( array( 'screen' => 'edit-comments' ) );
		$columns 	= $table->get_columns();		
		
		return $columns;
	}
	
	/**
     * Get Meta Keys
     *
	 * @since 1.5.0
	 *
	 * @return array
     */
    public function get_meta_keys() {
        global $wpdb;

		$fields = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( "cpac_get_meta_keys_{$this->key}", $this->maybe_add_hidden_meta( $fields ), $this->key );
    }
	
	/**
	 * Manage value
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $column_name, $comment_id ) {
		
		$value = '';
		
		// get column instance
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $comment_id );
		}
		
		// add hook		
		echo apply_filters( "cpac_value_{$this->key}", $value, $column );	
	}
	
}