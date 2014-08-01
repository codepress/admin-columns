<?php
/**
 * CPAC_Column_Post_Page_Template
 *
 * @since 2.0
 */
class CPAC_Column_Post_Formats extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-post_formats';
		$this->properties['label']	 	= __( 'Post Format', 'cpac' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'post-formats' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		if ( ! ( $format = $this->get_raw_value( $post_id ) ) ) {
			return false;
		}

		return esc_html( get_post_format_string( $format ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		if ( ! ( $format = get_post_format( $post_id ) ) ) {
			return false;
		}

		return $format;
	}
}