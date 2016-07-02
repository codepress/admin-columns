<?php
defined( 'ABSPATH' ) or die();

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
class CPAC_Column_Taxonomy extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-taxonomy';
		$this->properties['label'] = __( 'Taxonomy', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		return ac_helper()->post->get_terms_for_display( $post_id, $this->get_taxonomy() );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.3.4
	 */
	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {
		$post_type = $this->get_post_type();
		if ( ! $post_type || ! get_object_taxonomies( $post_type ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
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