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

		$this->properties['type'] = 'column-excerpt';
		$this->properties['label'] = __( 'Excerpt', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'post_excerpt';

		$this->options['excerpt_length'] = 30;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$value = $this->get_post_excerpt( $post_id, $this->get_option( 'excerpt_length' ) );
		if ( ! has_excerpt( $post_id ) && $value ) {
			$value = '<span class="cpac-inline-info">' . __( 'Excerpt from content', 'codepress-admin-columns' ) . '</span> ' . $value;
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_excerpt', $post_id, 'raw' );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {
		$this->display_field_excerpt_length();
	}
}