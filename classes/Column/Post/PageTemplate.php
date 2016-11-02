<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_PageTemplate extends AC_Column_PostAbstract {

	public function __construct() {
		$this->set_type( 'column-page_template' );
		$this->set_label( __( 'Page Template', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		return array_search( $this->get_raw_value( $post_id ), get_page_templates() );
	}

	function get_raw_value( $post_id ) {
		return get_post_meta( $post_id, '_wp_page_template', true );
	}

	function is_valid() {
		return 'page' === $this->get_post_type();
	}

}