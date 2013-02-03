<?php

/**
 * Addon class
 *
 * @since 0.1
 *
 */
class CAC_Addon_Sortable_Post {	
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct() {

		add_action( 'admin_init', array( $this, 'register_sortable_columns' ) );
	}
	
	/**
	 * 	Register sortable columns
	 *
	 *	Hooks into apply_filters( "manage_{$screen->id}_sortable_columns" ) which is found in class-wp-list-table.php
	 *
	 * 	@since 0.1
	 */
	function register_sortable_columns() {

		global $cpac;
		
		foreach ( $cpac->storage_models as $storage_model ) {
			
			if ( 'post' == $storage_model->type ) {
				add_filter( "manage_edit-{$storage_model->key}_sortable_columns", array( $this, 'manage_sortable_columns' ) );
			}
		}
	}	

	/**
	 * Callback add Posts sortable column
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function manage_sortable_columns( $columns ) {
		
		global $post_type, $cpac;
 
		// in some cases post_type is an array ( when clicking a tag inside the overview screen icm CCTM )
		// then we use this as a fallback so we get a string
		if ( is_array( $post_type ) )
			$post_type = $_REQUEST['post_type'];
		
		// storage model exists?
		if ( ! isset( $cpac->storage_models[ $post_type ] ) )
			return $columns;
		
		$storage_model = $cpac->storage_models[ $post_type ];

		if ( $_columns = $storage_model->get_columns() ) {
			foreach ( $_columns as $column ) {
				
				// column needs sort?
				if ( 'on' == $column->options->state && isset( $column->options->sort  ) && 'on' == $column->options->sort )				
					$columns[ $column->properties->name ] = CPAC_Utility::sanitize_string( $column->options->label );
			}
		}

		return $columns;
	}
	
}

/**
 * Init Class CAC_Addon_Sorting
 *
 * @since 0.1
 */
new CAC_Addon_Sortable_Post();