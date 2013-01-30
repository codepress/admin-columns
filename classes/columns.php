<?php

class CPAC_Columns {
		
	private $types 		= array();
	public $columns 	= array();
	
	function __construct() {
		
		$this->types = array(
			'user',
			'link',
			'comment',
			'media',
			'post'
		);
		
		$this->set_custom_columns();
		//set_default_columns();					
	}
	
	/**
	 * Get Types
	 *
	 * @since 2.0.0
	 */
	function get_types() {
		return $this->types;
	}
	
	/**
	 * Get Columns
	 *
	 * @since 2.0.0
	 */
	function get_columns() {
		return $this->columns;
	}
	
	/**
	 * Get Class Names
	 *
	 * @since 2.0.0
	 */
	function get_column_classnames( $type ) {
		
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
		
		if ( ! isset( $column_files[$type] ) )
			return array();			
				
		return $column_files[$type];
	}
	
	/**
	 * Set Custom Columns
	 *
	 * @since 2.0.0
	 */
	function set_custom_columns() {
	
		foreach ( $this->types as $type ) {

			$column_files = $this->get_column_classnames( $type );
			
			if ( 'post' == $type ) {
				
				foreach ( CPAC_Utility::get_post_types() as $post_type ) {
										
					$column_type = new stdClass;					
					
					// label
					$posttype_obj = get_post_type_object( $post_type );
					$column_type->label = $posttype_obj->labels->singular_name;			
					
					// create instances
					foreach	( $column_files as $file ) {
								
						require_once $file['path'];
						$column = new $file['classname']( $post_type );						
						$column_type->storage_key 	= $column->properties->storage_key;								
						$column_type->columns[] 	= $column;
						
						$this->columns[] = $column_type;
					}					
				}			
			}
			
			else {
			
				foreach	( $column_files as $file ) {
					
					$column_type = new stdClass;
					$column_type->label = ucfirst( $type );
					
					require_once $file['path'];
					$column = new $file['classname'];						
					$column_type->storage_key 	= $column->properties->storage_key;								
					$column_type->columns[] 	= $column;								
					
					$this->columns[] = $column_type;
				}
			}		
		}	
	}
	
	
}