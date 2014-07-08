<?php
/**
 * CPAC_Column_Link_Image
 *
 * @since 2.0
 */
class CPAC_Column_Link_Image extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-image';
		$this->properties['label']	 	= __( 'Image', 'cpac' );

		// Options
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return implode( $this->get_thumbnails( $bookmark->link_image ) );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_preview_size();
	}
}