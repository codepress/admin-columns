<?php

/**
 * CPAC_Column_Post_Page_Template
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Formats extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-post-formats';
		$this->properties['label']	 	= __( 'Post Format', CPAC_TEXTDOMAIN );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
	
		return get_post_format( $post_id );
	}
}