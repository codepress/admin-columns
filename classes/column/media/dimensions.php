<?php
/**
 * CPAC_Column_Media_Dimensions
 *
 * @since 2.0
 */
class CPAC_Column_Media_Dimensions extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-dimensions';
		$this->properties['label']	 	= __( 'Dimensions', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {

		$value = '';

		$meta = $this->get_raw_value( $id );

		if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			$value = "{$meta['width']} x {$meta['height']}";
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.2
	 */
	public function get_raw_value( $id ) {

		return get_post_meta( $id, '_wp_attachment_metadata', true );
	}
}