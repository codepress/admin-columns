<?php
defined( 'ABSPATH' ) or die();

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
abstract class AC_Column_TaxonomyAbstract extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-taxonomy' );
		$this->set_label( __( 'Taxonomy', 'codepress-admin-columns' ) );
	}

	// Display
	public function get_value( $post_id ) {
		return ac_helper()->post->get_terms_for_display( $post_id, $this->get_taxonomy() );
	}

	public function get_raw_value( $post_id ) {
		return wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );
	}

	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	// Settings
	public function display_settings() {
		$this->field_settings->field( array(
			'type'    => 'select',
			'name'    => 'taxonomy',
			'label'   => __( "Taxonomy", 'codepress-admin-columns' ),
			'options' => ac_helper()->taxonomy->get_taxonomy_selection_options( $this->get_post_type() ),
			'section' => true,
		) );
	}

	public function get_post_type() {
		return method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;
	}

}