<?php

/**
 * @since 2.0
 */
class AC_Column_Post_FeaturedImage extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-featured_image' );
		$this->set_label( __( 'Featured Image', 'codepress-admin-columns' ) );
	}

	// Meta

	public function get_meta_key() {
		return '_thumbnail_id';
	}

	// Display

	public function get_value( $post_id ) {
		$value = parent::get_value( $post_id );

		if ( ! $value ) {
			return false;
		}

		if ( $link = get_edit_post_link( $post_id ) ) {
			$value = ac_helper()->html->link( $link . '#postimagediv', $value );
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