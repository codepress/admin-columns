<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_Width extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-width';
		$this->properties['label'] = __( 'Width', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		return $value ? $value . 'px' : $this->get_empty_char();
	}

	function get_raw_value( $id ) {
		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		return ! empty( $meta['width'] ) ? $meta['width'] : false;
	}

}