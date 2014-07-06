<?php
/**
 * CPAC_Column_Post_Excerpt
 *
 * @since 2.0
 */
class CPAC_Column_Post_Excerpt extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']				= 'column-excerpt';
		$this->properties['label']				= __( 'Excerpt', 'cpac' );
		$this->properties['object_property']	= 'post_excerpt';

		// Options
		$this->options['excerpt_length'] = 30;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
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
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}