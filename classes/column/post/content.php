<?php
/**
 * CPAC_Column_Post_Content
 *
 * @since 2.4
 */
class CPAC_Column_Post_Content extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.4
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']				= 'column-content';
		$this->properties['label']				= __( 'Content', 'codepress-admin-columns' );
		$this->properties['object_property']	= 'post_content';

		// Options
		$this->options['excerpt_length'] = 30;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.4
	 */
	public function get_value( $post_id ) {

		return $this->get_shortened_string( $this->get_raw_value( $post_id ), $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.4
	 */
	public function get_raw_value( $post_id ) {

		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.4
	 */
	public function display_settings() {

		$this->display_field_excerpt_length();
	}
}