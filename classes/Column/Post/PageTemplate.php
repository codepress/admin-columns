<?php

/**
 * @since 2.0
 */
class AC_Column_Post_PageTemplate extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-page_template' );
		$this->set_label( __( 'Page Template', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_wp_page_template';
	}

	function get_value( $post_id ) {
		return array_search( $this->get_raw_value( $post_id ), get_page_templates() );
	}

	// TODO: 4.7 supports post_type templates
	function is_valid() {
		return 'page' === $this->get_post_type();
	}

}