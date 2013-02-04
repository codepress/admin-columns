<?php
/**
 * Post Excerpt
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_ID extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-postid';
		$this->properties['label']	 	= __( 'Post ID', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 *
	 * @todo image size
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		return $post_id;	
	}	
	
	/**
	 * Display Settings
	 *
	 * @todo: add field excerpt length
	 * @since 2.0.0
	 */
	function display_settings() {}
}