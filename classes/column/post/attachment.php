<?php
/**
 * CPAC_Column_Post_Attachment
 *
 * @since 2.0
 */
class CPAC_Column_Post_Attachment extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-attachment';
		$this->properties['label']	 = __( 'Attachment', 'cpac' );

		// Options
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		$attachment_ids = $this->get_raw_value( $post_id );

		return implode( $this->get_thumbnails( $attachment_ids, $this->options ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_posts( array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_preview_size();
	}
}