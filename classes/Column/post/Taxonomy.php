<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Taxonomy extends AC_Column_TaxonomyAbstract {

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

	public function get_value( $post_id ) {
		return ac_helper()->post->get_terms_for_display( $post_id, $this->get_taxonomy() );
	}

	public function get_raw_value( $post_id ) {
		return wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );
	}

}