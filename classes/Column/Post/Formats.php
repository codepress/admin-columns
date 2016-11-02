<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Formats extends AC_Column_PostAbstract {

	// TODO: OLD
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-post_formats';
		$this->properties['label'] = __( 'Post Format', 'codepress-admin-columns' );
	}

	// TODO: NEW
	public function __construct() {
		$this->set_type( 'column-post_formats' );
		$this->set_label( __( 'Post Format', 'codepress-admin-columns' ) );
	}

	function is_valid() {
		return post_type_supports( $this->get_post_type(), 'post-formats' );
	}

	function get_value( $post_id ) {
		$format = $this->get_raw_value( $post_id );

		return $format ? esc_html( get_post_format_string( $format ) ) : __( 'Standard', 'codepress-admin-columns' );
	}

	function get_raw_value( $post_id ) {
		$format = get_post_format( $post_id );

		return $format ? $format : false;
	}

}