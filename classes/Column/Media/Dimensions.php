<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_Dimensions extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-dimensions';
		$this->properties['label'] = __( 'Dimensions', 'codepress-admin-columns' );
	}

	public function get_surface( $id ) {
		$meta = $this->get_raw_value( $id );

		$height = ! empty( $meta['height'] ) ? $meta['height'] : 0;
		$width = ! empty( $meta['width'] ) ? $meta['width'] : 0;

		return $height * $width;
	}

	public function get_value( $id ) {
		$value = $this->get_empty_char();

		$meta = $this->get_raw_value( $id );

		if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			$value = "{$meta['width']} x {$meta['height']}";
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return get_post_meta( $id, '_wp_attachment_metadata', true );
	}

}