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

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'thumbnail' );
	}

	public function get_value( $post_id ) {
		$attachment_id = $this->get_raw_value( $post_id );
		$thumb = $this->get_settings()->image->format( $attachment_id );

		if ( ! $thumb ) {
			return false;
		}

		if ( $link = get_edit_post_link( $post_id ) ) {
			$thumb = ac_helper()->html->link( $link . '#postimagediv', $thumb );
		}

		return $thumb;
	}

	/**
	 * Returns Attachment ID
	 *
	 * @param int $post_id
	 *
	 * @return int|false
	 */
	public function get_raw_value( $post_id ) {
		return has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : false;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Image( $this ) );
	}

}