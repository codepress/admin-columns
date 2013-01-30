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
	 * Get Class Names
	 *
	 * @since 2.0.0
	 */
	function get_classinfo_columns() {
		
		if ( ! $column_files = get_transient( 'cpac_classnames' ) ) {
		
			// Column directory
			$directory = CPAC_DIR . 'classes/column';

			$file = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ) );

			while( $file->valid() ) {

				if ( ! $file->isDot() ) {
					
					// details
					$dir 		= $file->getSubPath();
					$file_name  = pathinfo( $file->getSubPathName(), PATHINFO_FILENAME );
					$file_path 	= $file->key();
					
					// class name
					$parts 		= explode( '-', $file_name );
					$parts 		= array_map( 'ucfirst', $parts );
					$class_name = 'CPAC_Column_' . ucfirst( $dir ). '_' . implode( '_', $parts );					
					
					// create instance	
					$column_files[$dir][] = array(
						'classname'	=> $class_name,
						'path'		=> $file_path
					);
				}
				
				$file->next();
			}
			
			set_transient( 'cpac_classnames', $column_files, 3600 );
		}
		
		if ( ! isset( $column_files[$this->meta_type] ) )
			return array();

		return $column_files[$this->meta_type];
	}
	
	/**
	 * Get default columns
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();
	
	/**
	 * Get stored columns
	 *
	 * @since 2.0.0
	 */
	function get_stored_columns() {
		$columns = array();
		
		// get plugin options
		$options = get_option('cpac_options');

		// get saved columns
		if ( empty( $options['columns'][$this->storage_key] ) )
			return false;

		return $options['columns'][$this->storage_key];		
	}	
	
	/**
	 * Get custom columns
	 *
	 */
	public function get_columns( $is_active = false ) {
		
		$columns = array();
		
		// get default columns		
		if( $default_columns = $this->get_default_columns() )
			foreach ( $default_columns as $column_name => $column_label ) {		
				$columns[] = new CPAC_Column( $this->storage_key, $column_name, $column_label );			
			}
		}
		
		// get custom columns		
		if ( $files = $this->get_classinfo_columns() ) {
			foreach	( $files as $file ) {
						
				require_once $file['path'];
				$columns[] = new $file['classname']( $this->storage_key );						
			}
		}
		
		// get stored columns
		$stored_columns = CPAC_Utility::get_stored_columns();
		
		return $columns;		
	}
}