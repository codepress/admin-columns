<?php
/**
 * CPAC_Column_Post_Before_Moretag
 *
 * @since 2.0
 */
class CPAC_Column_Post_Before_Moretag extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-before_moretag';
		$this->properties['label']	 = __( 'Before More Tag', 'cpac' );

		// Options
		$this->options['excerpt_length'] = 15;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		$value = '';

		$p = get_post( $post_id );
		$extended = get_extended( $p->post_content );

		if ( ! empty( $extended['extended'] ) ) {
			$value = $this->get_shortened_string( $extended['main'], $this->options->excerpt_length );
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}