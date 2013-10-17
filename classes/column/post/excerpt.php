<?php
/**
 * CPAC_Column_Post_Excerpt
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Excerpt extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']				= 'column-excerpt';
		$this->properties['label']				= __( 'Excerpt', 'cpac' );
		$this->properties['object_property']	= 'post_excerpt';

		// define additional options
		$this->options['excerpt_length'] = 30;

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		return $this->get_post_excerpt( $post_id, $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'post_excerpt', $post_id, 'raw' );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}