<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Slug extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-slug' );
		$this->set_label( __( 'Slug', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_name', $post_id, 'raw' );
	}

}