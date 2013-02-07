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
	 * @since 2.0.0
	 */
	public $label;
		
	/**
	 * Key
	 *
	 * @since 2.0.0
	 */
	public $key;
	
	/**
	 * Type
	 *
	 * @since 2.0.0
	 */
	public $type;
	
	/**
	 * Get default columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();
	
	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {}
	
	/**
	 * Maybe add hidden meta - Utility Method
	 *
	 * @since 1.5
	 *
	 * @param array $fields Custom fields.
	 * @return array Custom fields.
	 */
	protected function maybe_add_hidden_meta( $fields ) {
		if ( ! $fields )
			return false;

		$combined_fields = array();

		$use_hidden_meta = apply_filters( 'cpac_use_hidden_custom_fields', false );

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identifaction
			if ( $use_hidden_meta && substr( $field[0], 0, 1 ) == "_") {
				$combined_fields[] = 'cpachidden'.$field[0];
			}

			// non hidden fields are saved as is
			elseif ( substr( $field[0], 0, 1 ) != "_" ) {
				$combined_fields[] = $field[0];
			}
		}

		if ( empty( $combined_fields ) )
			return false;

		return $combined_fields;
	}
	
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
		
		$columns = array_filter( $_POST['columns'] );
		
		// reorder by active state
		// @todo make a general setting to reorder
		if ( true ) {
			$active = $inactive = array();
			
			foreach ( $columns as $column_name => $options ) {
				if ( 'on' == $options['state'] ) {
					$active[ $column_name ] = $options;
				}
				else {
					$inactive[ $column_name ] = $options;
				}
			}

			$columns = array_merge( $active, $inactive );
		}
		
		update_option( "cpac_options_{$this->key}", $columns );
		
		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully updated.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );	
	}
	
	/**
	 * Get custom columns
	 *
	 * Goes through all files in 'classes/column' and includes each file.
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Classnames
	 */
	function get_custom_columns() {			
		$columns = array();
		
		$file = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( CPAC_DIR . 'classes/column' ) );

		while( $file->valid() ) {

			if ( ! $file->isDot() && $this->type == $file->getSubPath() ) {
				
				include_once $file->key();
		
				// build classname from filename
				$type = ucfirst( $file->getSubPath() );
				$name = implode( '_', array_map( 'ucfirst', explode( '-', basename( $file->key(), '.php' ) ) ) );
				
				$columns[] = "CPAC_Column_{$type}_{$name}";
			}

			$file->next();
		}
		
		// hooks for adding custom columns by addons
		$columns = apply_filters( "cpac_custom_columns_posts", $columns );
		$columns = apply_filters( "cpac_custom_columns_{$this->key}", $columns );
		
		return $columns;
	}
	
	/**
	 * Get registered columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_registered_columns() {
		
		$columns = array();
		
		// Defaults
		foreach ( $this->get_default_columns() as $column_name => $label ) {
			
			// checkboxes are mandatory
			if ( 'cb' == $column_name )
				continue;			
			
			$column = new CPAC_Column( $this );
			$column
				->set_properties( 'type', $column_name )
				->set_properties( 'name', $column_name )				
				->set_properties( 'label', $label )
				->set_options( 'label', $label )				
				->set_options( 'state', 'on' );
			
			// Exceptions for: Checkbox, Comments and Icon
			if ( in_array( $column_name, array( 'comments', 'icon' ) ) ) {
				$column->set_properties( 'hide_label', true );
				if ( 'icon' == $column_name ) {
					$column->set_properties( 'label', 'Icon' );
				}
			}
		
			$columns[ $column->properties->name ] = $column;			
		}		
		
		// Custom
		foreach ( $this->get_custom_columns() as $classname ) {
			
			$column = new $classname( $this );
			
			// some column are not registered based on conditional logic within the child column
			if ( ! $column->properties->is_registered )
				continue;
			
			$columns[ $column->properties->name ] = $column;
		}
		
		do_action( "cpac_get_columns", $columns );
		do_action( "cpac_get_columns_{$this->key}", $columns );
		
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
	public function get_stored_columns() {

		if ( ! $columns = get_option( "cpac_options_{$this->key}" ) )
			return array();
			
		return $columns;
	}
	
	/**
	 * Get Columns
	 *
	 * @since 2.0.0
	 */	 
	function get_columns() {
	
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
					
				// remember which types has been used, so we can filter them later
				$stored_types[] = $options['type'];
				
				// In case of a disabled plugin, we will skip column.
				// This means the stored column type is not available anymore.
				if ( ! in_array( $options['type'], array_keys( $registered_columns ) ) )
					continue;
				
				// create clone				
				$column = clone $registered_columns[ $options['type'] ];
				
				// add an clone number which defines the instance
				$column->set_clone( $options['clone'] );
				
				// repopulate the options, so they contains the right stored options
				$column->populate_options();
					
				$columns[ $name ] = $column;								
			}
			
			// In case of an enabled plugin or added custom column, we will add that column.
			// When $diff contains items, it means an registered column has not been stored.
			if ( $diff = array_diff( array_keys( $registered_columns ), $stored_types ) ) {
				foreach ( $diff as $type ) {
					$columns[ $type ] = clone $registered_columns[ $type ];
				}
			}			
		}
				
		// When nothing has been saved yet, we return the available columns.
		else {
		
			$columns = $registered_columns;
		}

		return $columns;		
	}
	
	/**
	 * Get Column by name
	 *
	 * @since 2.0.0
	 */	 
	function get_column_by_name( $name ) {
		
		$columns = $this->get_columns();
		
		if ( ! isset( $columns[ $name ] ) )
			return false;
		
		return $columns[ $name ];
	}
	
	/**
	 * Render
	 *
	 * @since 2.0.0
	 */	 
	function render() {		
		
		foreach ( $this->get_columns() as $column ) {			
			$column->display();
		}	
	}
	
	/**
	 * Add Headings
	 *
	 * @todo: add column headings that could not be stored from some reason.
	 * @since 2.0.0
	 */
	function add_headings( $columns ) {
		
		global $pagenow;
		
		// only add headings on overview screens, to prevent turning off columns in the Storage Model.
		if ( 'admin.php' == $pagenow )
			return $columns;
		
		// stored columns exists?
		if ( ! $stored_columns = get_option( "cpac_options_{$this->key}" ) )
			return $columns;
		
		// build the headings
		$column_headings = array();
		
		// add mandatory checkbox
		if ( isset( $columns['cb'] ) )
			$column_headings['cb'] = $columns['cb'];
		
		// add active stored headings
		foreach( $stored_columns as $column_name => $options ) {			
			if ( isset( $options[ 'state'] ) && 'on' == $options['state'] ) {
			
				// label needs stripslashes() for HTML tagged labels, like icons and checkboxes
				$column_headings[ $column_name ] = stripslashes( $options['label'] );				
			}
		}
		
		// Add 3rd party columns that have ( or could ) not been stored. 
		// For example when a plugin has been activated.
		// When $diff contains items, it means an available column has not been stored.
		if ( $diff = array_diff( array_keys( $columns ), array_keys( $stored_columns ) ) ) {	
			foreach ( $diff as $column_name ) {
				$column_headings[ $column_name ] = $columns[ $column_name ];
			}
		}

		return $column_headings;
	}
}