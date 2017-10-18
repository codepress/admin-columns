<?php

/**
 * @since 2.0
 */
class AC_Column_Media_ExifData extends AC_Column_Media_MetaValue {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-exif_data' );
		$this->set_label( __( 'EXIF Data', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'image_meta';
	}

	public function is_valid() {
		return function_exists( 'exif_read_data' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_ExifData( $this ) );
	}

}