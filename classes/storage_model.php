<?php

/**
 * Storage Model
 *
 * @since 2.0.0
 */
abstract class CPAC_Storage_Model {
	
	/**
	 * Label
	 *
	 */
	public $label;
		
	/**
	 * Key
	 *
	 */
	public $key;
	
	/**
	 * Get default columns
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();
	
	/**
	 * Get custom columns
	 *
	 * @return array Classname | Path
	 */
	abstract function get_custom_columns();
	
	/**
	 * Constructor
	 *
	 */
	function __construct() {}
	
	/**
	 * Restore
	 *
	 * @since 2.0.0
	 */	 
	function restore() {	
	
		delete_option( "cpac_options_{$this->key}" );
		
		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully restored.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );	
	}
	
	/**
	 * Store
	 *
	 * @since 2.0.0
	 */
	function store() {

		if ( empty( $_POST['columns'] ) )
			return false;
		
		update_option( "cpac_options_{$this->key}", array_filter( $_POST['columns'] ) );
		
		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully updated.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );	
		
		// DEV @todo: Remove dev
		print_r( $_POST['columns'] );
		print_r( get_option( "cpac_options_{$this->key}" ) );
	}
	
	/**
	 * Get registered columns
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_registered_columns() {
		
		$columns = array();
		
		foreach ( $this->get_default_columns() as $column_name => $label ) {
			
			$column = new CPAC_Column( $this );			
			$column
				->set_type( $column_name )
				->set_label( $label )
				->set_state( 'on' );
			
			if ( 'cb' == $column_name )
				$column->set_editable_label( false );
			
			$columns[ $column->properties->name ] = $column;			
		}
		
		foreach ( $this->get_custom_columns() as $classname => $path ) {
			include_once $path;	
			
			$column = new $classname( $this );
			$columns[ $column->properties->name ] = $column;
		}
		
		return $columns;
	}
	
	/**
	 * Get column options from DB
	 *
	 * @since 1.0.0
	 *
	 * @paran string $key
	 * @return array Column options
	 */
	private function get_stored_columns() {

		if ( ! $columns = get_option( "cpac_options_{$this->key}" ) )
			return array();
			
		return $columns;
	}
	
	/**
	 * Render
	 *
	 * @since 2.0.0
	 */	 
	function render() {		
		
		$columns = array();
		
		// get columns
		$registered_columns = $this->get_registered_columns();
		$stored_columns 	= $this->get_stored_columns();	
		
		// Stored columns
		if ( $stored_columns ) {
			
			$stored_types = array(); 
			
			foreach ( $stored_columns as $name => $options ) {
								
				if ( ! isset( $options['type'] ) )
					continue;
				
				// column type
				$type = $options['type'];
				
				// remember which types has been used
				$stored_types[] = $type;
				
				// In case of a disabled plugin, we will skip column.
				// This means the stored column type is not available anymore.
				if ( ! in_array( $type, array_keys( $registered_columns ) ) )
					continue;
				
				// create clone			
				$column = clone $registered_columns[ $type ];
				$column->set_clone( $options['clone'] );
				
				print_R( $column );
				
				$columns[] = $column;								
			}			
			
			
			// @todo: set_clone() seems to overwrite all instance of $column....
			print_r( $columns );
			exit;
		}
		
		// When nothing has been saved yet, we return the available columns.
		else {
		
			$columns = $registered_columns;
		}
		
		// render		
		foreach ( $columns as $column ) {			
			$column->display();
		}	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}