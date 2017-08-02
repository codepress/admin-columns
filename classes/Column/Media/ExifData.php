<?php

/**
 * @since 2.0
 */
class AC_Column_Media_ExifData extends AC_Column_Media_Meta {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-exif_data' );
		$this->set_label( __( 'EXIF Data', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		$value = parent::get_raw_value( $id );

		if ( empty( $value['image_meta'] ) ) {
			return false;
		}

		return $value['image_meta'];
	}

	public function is_valid() {
		return function_exists( 'exif_read_data' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_ExifData( $this ) );
	}

}