<?php

/**
 * CPAC_Column_Media_Custom_Field
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Custom_Field extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-meta';
		$this->properties['label']	 	= __( 'Custom Field', CPAC_TEXTDOMAIN );
		$this->properties['classes']	= 'cpac-box-metafield';		
		
		// define additional options
		$this->options['field']			= '';
		$this->options['field_type']	= '';
		$this->options['before']		= '';
		$this->options['after']			= '';
		
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;	
		
		// call contruct
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {
	
		return $this->get_value_custom_field( $id );
	}	
	
	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {
				
		$this->display_custom_field();
	}
}