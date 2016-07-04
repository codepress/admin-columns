<?php
defined( 'ABSPATH' ) or die();

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
class AC_Column_TaxonomyAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-taxonomy';
		$this->properties['label'] = __( 'Taxonomy', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
	}

	public function get_value( $post_id ) {
		return ac_helper()->post->get_terms_for_display( $post_id, $this->get_taxonomy() );
	}

	public function get_raw_value( $post_id ) {
		return wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );
	}

	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	public function apply_conditional() {
		return ac_helper()->post->is_registered_by_post_type( $this->get_post_type() );
	}

	public function display_settings() {
		$taxonomies = get_object_taxonomies( $this->get_post_type(), 'objects' );

		$options = array();
		foreach ( $taxonomies as $index => $taxonomy ) {
			if ( $taxonomy->name == 'post_format' ) {
				unset( $taxonomies[ $index ] );
			}
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		$this->form_field( array(
			'type'    => 'select',
			'name'    => 'taxonomy',
			'label'   => __( "Taxonomy", 'codepress-admin-columns' ),
			'options' => $options,
			'section' => true,
		) );
	}

}