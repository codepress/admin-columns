<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Dimensions extends AC_Column_Media_Meta {

	public function __construct() {
		$this->set_type( 'column-dimensions' );
		$this->set_label( __( 'Dimensions', 'codepress-admin-columns' ) );
	}

	public function get_surface( $id ) {
		$meta = $this->get_raw_value( $id );

		$height = ! empty( $meta['height'] ) ? $meta['height'] : 0;
		$width = ! empty( $meta['width'] ) ? $meta['width'] : 0;

		return $height * $width;
	}

	public function get_value( $id ) {
		$value = ac_helper()->string->get_empty_char();

		$meta = $this->get_raw_value( $id );

		if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			$value = "{$meta['width']} x {$meta['height']}";
		}

		return $value;
	}

}