<?php

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
class AC_Column_Taxonomy extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-taxonomy' );
		$this->set_label( __( 'Taxonomy', 'codepress-admin-columns' ) );
	}

	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	// Display

	public function get_value( $post_id ) {
		return implode( __( ', ' ), ac_helper()->taxonomy->get_term_links( get_the_terms( $post_id, $this->get_taxonomy() ), get_post_type( $post_id ) ) );
	}

	public function get_raw_value( $post_id ) {
		$terms = wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return false;
		}

		return $terms;
	}

	// Settings

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Taxonomy( $this ) );
	}

}