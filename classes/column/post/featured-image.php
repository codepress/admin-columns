<?php
/**
 * CPAC_Column_Post_Featured_Image
 *
 * @since 2.0
 */
class CPAC_Column_Post_Featured_Image extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	= 'column-featured_image';
		$this->properties['label']	= __( 'Featured Image', 'cpac' );

		// Options
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.2
	 */
	public function apply_conditional() {

		return post_type_supports( $this->storage_model->key, 'thumbnail' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		$thumbnail_id = $this->get_raw_value( $post_id );

		if ( ! $thumbnail_id ) {
			return false;
		}

		$thumb = implode( $this->get_thumbnails( $thumbnail_id, (array) $this->options ) );
		$link  = get_edit_post_link( $post_id );

		return $link ? "<a href='{$link}#postimagediv'>{$thumb}</a>" : $thumb;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {

		if ( ! has_post_thumbnail( $post_id ) ) {
			return false;
		}

		return get_post_thumbnail_id( $post_id );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {

		$this->display_field_preview_size();
	}
}