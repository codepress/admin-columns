<?php

/**
 * @since 2.0
 */
class AC_Column_Media_ExifData extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-exif_data' );
		$this->set_label( __( 'EXIF data', 'codepress-admin-columns' ) );
	}

	public function get_value( $object_id ) {
		return $this->format_value( $this->get_image_meta( $object_id ) );
	}

	public function get_raw_value( $id ) {
		return $this->get_image_meta( $id );
	}

	private function get_image_meta( $id ) {
		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		return ! empty( $meta['image_meta' ] ) ? $meta['image_meta' ] : false;
	}

	public function is_valid() {
		return function_exists( 'exif_read_data' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_ExifData( $this ) );
	}

}