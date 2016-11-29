<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_ExifData extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-exif_data' );
		$this->set_label( __( 'EXIF data', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = '';

		// TODO: move to setting formatter?
		$data = $this->get_option( 'exif_datatype' );
		$meta = $this->get_raw_value( $id );

		if ( isset( $meta['image_meta'][ $data ] ) ) {
			$value = $meta['image_meta'][ $data ];

			if ( 'created_timestamp' == $data ) {
				$value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $value ) );
			}
		}
		if ( empty( $value ) ) {
			$value = ac_helper()->string->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return get_post_meta( $id, '_wp_attachment_metadata', true );
	}

	public function is_valid() {
		return function_exists( 'exif_read_data' );
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_ExifData( $this ) );
	}

}