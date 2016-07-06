<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_ExifData extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-exif_data';
		$this->properties['label'] = __( 'EXIF data', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
	}

	/**
	 * Get EXIF data
	 *
	 * Get extended image metadata
	 *
	 * @since 2.0
	 *
	 * @return array EXIF data types
	 */
	private function get_exif_types() {
		$exif_types = array(
			'aperture'          => __( 'Aperture', 'codepress-admin-columns' ),
			'credit'            => __( 'Credit', 'codepress-admin-columns' ),
			'camera'            => __( 'Camera', 'codepress-admin-columns' ),
			'caption'           => __( 'Caption', 'codepress-admin-columns' ),
			'created_timestamp' => __( 'Timestamp', 'codepress-admin-columns' ),
			'copyright'         => __( 'Copyright EXIF', 'codepress-admin-columns' ),
			'focal_length'      => __( 'Focal Length', 'codepress-admin-columns' ),
			'iso'               => __( 'ISO', 'codepress-admin-columns' ),
			'shutter_speed'     => __( 'Shutter Speed', 'codepress-admin-columns' ),
			'title'             => __( 'Title', 'codepress-admin-columns' ),
		);

		natcasesort( $exif_types );

		return $exif_types;
	}

	public function get_value( $id ) {
		$value = '';

		$data = $this->get_option( 'exif_datatype' );
		$meta = $this->get_raw_value( $id );

		if ( isset( $meta['image_meta'][ $data ] ) ) {
			$value = $meta['image_meta'][ $data ];

			if ( 'created_timestamp' == $data ) {
				$value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $value ) );
			}
		}
		if ( empty( $value ) ) {
			$value = $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return get_post_meta( $id, '_wp_attachment_metadata', true );
	}

	public function apply_conditional() {
		return function_exists( 'exif_read_data' );
	}

	public function display_settings() {
		$this->form_field( array(
			'type'    => 'select',
			'name'    => 'exif_datatype',
			'label'   => $this->get_type_label(),
			'options' => $this->get_exif_types(),
		) );
	}

}