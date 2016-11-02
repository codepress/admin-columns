<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Formats extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-post_formats' );
		$this->set_label( __( 'Post Format', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'post-formats' );
	}

	public function get_value( $post_id ) {
		$format = $this->get_raw_value( $post_id );

		return $format ? esc_html( get_post_format_string( $format ) ) : __( 'Standard', 'codepress-admin-columns' );
	}

	public function get_raw_value( $post_id ) {
		$format = get_post_format( $post_id );

		return $format ? $format : false;
	}

}