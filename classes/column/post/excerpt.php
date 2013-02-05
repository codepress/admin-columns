<?php
/**
 * Post Excerpt
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Excerpt extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-excerpt';
		$this->properties['label']	 	= __( 'Excerpt', CPAC_TEXTDOMAIN );
			
		// define additional options
		$this->options['excerpt_length'] = 30;		
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		$this->get_post_excerpt( $post_id );	
	}
}