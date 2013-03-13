<?php
/**
 * CPAC_Column_Link_Image
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Image extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-image';
		$this->properties['label']	 	= __( 'Image', 'cpac' );

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
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return implode( $this->get_thumbnails( $bookmark->link_image ) );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$this->display_field_preview_size();
	}
}