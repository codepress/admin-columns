<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Width extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-width' );
		$this->set_label( __( 'Width', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		return $value ? $value . 'px' : ac_helper()->string->get_empty_char();
	}

	public function get_raw_value( $id ) {
		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		return ! empty( $meta['width'] ) ? $meta['width'] : false;
	}

}