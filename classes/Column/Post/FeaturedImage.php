<?php

/**
 * @since 2.0
 */
class AC_Column_Post_FeaturedImage extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-featured_image' );
		$this->set_label( __( 'Featured Image', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_thumbnail_id';
	}

	public function get_value( $id ) {
		$value = parent::get_value( $id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	/**
	 * Returns Attachment ID
	 *
	 * @param int $post_id
	 *
	 * @return int|false
	 */
	public function get_raw_value( $post_id ) {
		if ( ! has_post_thumbnail( $post_id ) ) {
			return false;
		}

		return get_post_thumbnail_id( $post_id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Image( $this ) );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'thumbnail' );
	}

}