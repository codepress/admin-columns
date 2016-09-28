<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_FeaturedImage extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-featured_image';
		$this->properties['label'] = __( 'Featured Image', 'codepress-admin-columns' );
	}

	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'thumbnail' );
	}

	public function get_value( $post_id ) {
		$attachment_id = $this->get_raw_value( $post_id );
		$thumb = $this->format->images( $attachment_id );

		if ( ! $thumb ) {
			return false;
		}

		$link = get_edit_post_link( $post_id );

		return $link ? ac_helper()->html->link( $link . '#postimagediv', $thumb ) : $thumb;
	}

	public function get_raw_value( $post_id ) {
		return has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : false;
	}

	public function display_settings() {
		$this->field_settings->image();
	}

}