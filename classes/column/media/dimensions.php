<?php

/**
 * CPAC_Column_Media_Dimensions
 *
 * @since 2.0
 */
class CPAC_Column_Media_Dimensions extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-dimensions';
		$this->properties['label'] = __( 'Dimensions', 'codepress-admin-columns' );
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