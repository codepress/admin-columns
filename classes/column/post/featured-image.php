<?php

/**
 * Post Featured Image column
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Featured_Image extends CPAC_Column_Post {

	function __construct( $storage_key, $column_name ) {		

		// define additional options
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;
				
		// define properties	
		$this->properties['column_name'] = 'column-featured_image';
		$this->properties['type_label']	 = __( 'Featured Image', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_key, $column_name );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 *
	 * @todo image size
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		if ( ! has_post_thumbnail( $post_id ) )
			return false;
			
		return $this->get_thumbnails( get_post_thumbnail_id( $post_id ), $this->options );		
	}
	
	/**
	 * @see CPAC_Column::get_sortable_vars()
	 * @since 2.0.0
	 */
	function get_sortable_vars( $vars, $posts = array() ) {
		$unsorted = array();

		foreach ( $posts as $p ) {
			$unsorted[$p->ID] = $p->ID;
			
			if ( ! has_post_thumbnail( $p->ID ) ) {
				$unsorted[$p->ID] = 0;
			}
		}

		return CPAC_Sortable::get_vars_post__in( $vars, $unsorted, SORT_REGULAR );
	}
	
	/**
	 * Display Settings
	 *
	 * @since 2.0.0
	 */
	function display_settings() {
	
		$this->display_field_preview_size();
	}
}