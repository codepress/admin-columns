<?php

/**
 * CPAC_Column_Post_Featured_Image
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Featured_Image extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-featured_image';
		$this->properties['label']	 	= __( 'Featured Image', 'cpac' );

		// define additional options
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		if ( ! has_post_thumbnail( $post_id ) )
			return false;

		$thumb = implode( $this->get_thumbnails( get_post_thumbnail_id( $post_id ), (array) $this->options ) );
		$link  = get_edit_post_link( $post_id );

		return $link ? "<a href='{$link}#postimagediv'>{$thumb}</a>" : $thumb;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$this->display_field_preview_size();
	}
}