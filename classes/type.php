<?php

/**
 * Get Type
 *
 * @since 2.0.0
 */
abstract class CPAC_Type {
	
	/**
	 * Storage key
	 *
	 */
	public $storage_key;
		
	/**
	 * Meta type
	 *
	 */
	public $meta_type;
	
	/**
	 * Label
	 *
	 */
	public $label;
	
	/**
	 * Columns 
	 *
	 */	 
	protected $columns;

	/**
	 * Get default columns
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();
	
	/**
	 * Constructor
	 *
	 */
	function __construct() {		
		$this->set_columns();
	}
	
	/**
	 * Get Class Names
	 *
	 * @since 2.0.0
	 */
	function set_columns() {
		
		$columns = get_transient( "cpac_classnames_{$this->meta_type}" );			
		$columns = '';			
		
		if ( ! $columns ) {
		
			$directory = CPAC_DIR . 'classes/column';

			$file = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ) );

			while( $file->valid() ) {

				if ( ! $file->isDot() ) {

					$dir = $file->getSubPath();
					
					if ( $dir == $this->meta_type ) {
						
						// file details
						$file_name  = pathinfo( $file->getSubPathName(), PATHINFO_FILENAME );
						$file_path 	= $file->key();
						
						// class name
						$parts 		= explode( '-', $file_name );
						$parts 		= array_map( 'ucfirst', $parts );
						$class_name = 'CPAC_Column_' . ucfirst( $dir ). '_' . implode( '_', $parts );
						
						// create instance
						include_once $file_path;
						$col = new $class_name( $this->storage_key );
			
						$columns[$col->properties->column_name] = array(
							'class_name'	=> $class_name,
							'path'			=> $file_path
						);
					}
				}
				
				$file->next();
			}
			
			set_transient( 'cpac_classnames', $columns, 3600 );
		}
	
		// include files and set columns
		foreach ( $columns as $column_name => $info ) {
			
			include_once $info['path'];
			$this->columns[$column_name] = $info['class_name'];			
		}

		foreach ( $this->get_default_columns() as $column_name => $label ) {
			$this->columns[$column_name] = 'CPAC_Column_Default';
		}
	}
	
	/**
	 * Get column options from DB
	 *
	 * @since 1.0.0
	 *
	 * @paran string $storage_key
	 * @return array Column options
	 */
	private function get_stored_columns() {
	
		$columns = get_option( "cpac_options_{$this->storage_key}" );
		
		if ( ! $columns )
			return array();
			
		return $columns;
	}
	
	/**
	 * Save columns
	 *
	 * @since 2.0.0
	 */
	function save_columns() {
		
		if ( empty( $_POST['cpac_options'][$this->storage_key] ) )
			return false;
		
		update_option( "cpac_options_{$this->storage_key}", array_filter( $_POST['cpac_options'][$this->storage_key] ) );

		print_r( get_option( "cpac_options_{$this->storage_key}" ) );
	}
	
	/**
	 * Get columns
	 *
	 * @since 2.0.0
	 */
	public function get_columns( $is_active = false ) {
		
		/* $columns = array();
		
		// get default columns		
		if( $wp_default_columns = $this->get_default_columns() ) {
			foreach ( $wp_default_columns as $column_name => $column_label ) {	

				$column = new CPAC_Column_Default( $this->storage_key, $column_name, $column_label );

				$columns[$column->properties->column_name] = $column;				
			}
		}
		
		// get custom columns		
		if ( $files = $this->get_classinfo_columns() ) {
			foreach	( $files as $file ) {
						
				require_once $file['path'];
				$column = new $file['classname']( $this->storage_key );
				
				$columns[$column->properties->column_name] = $column;
			}
		} */
		
		$stored_columns = $this->get_stored_columns();
		
		$diff = array_diff( array_keys( $stored_columns ), array_keys( $this->columns ) );
		
		print_r( $this->columns );
		print_r( $diff );
		exit;
		
		
		
		// get stored columns
		if ( $stored_columns = $this->get_stored_columns() ) {
			foreach ( $stored_columns as $column_name => $column_clones ) {
				foreach ( $column_clones as $clone_key => $options ) {
					
					
					
				}			
			}
		}		
		
		return $columns;		
	}
}