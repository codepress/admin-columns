<?php

class CPAC_Storage_Model_Comment extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {		
		
		$this->key 		= 'comments';		
		$this->label 	= __( 'Comments' );
		
		// give higher priority, so it will load just before other plugins to prevent conflicts
		add_filter( "manage_edit-comments_columns", array( $this, 'add_columns_headings_comments' ) );
		
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
	function get_default_columns() {

	}
	
	/**
	 * Get custom columns.
	 *
	 * @see CPAC_Storage_Model::get_custom_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_custom_columns() {
	
	}
	
}