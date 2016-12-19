<?php

class AC_Settings_Setting_ExifData extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $exif_datatype;

	protected function set_name() {
		$this->name = 'exif_data';
	}

	protected function define_options() {
		return array( 'exif_datatype' );
	}

	public function create_view() {
		return new AC_View( array(
			'label'   => $this->column->get_label(),
			'setting' => $this->create_element( 'select' )->set_options( $this->get_exif_types() ),
		) );
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

	/**
	 * @return string
	 */
	public function get_exif_datatype() {
		return $this->exif_datatype;
	}

	/**
	 * @param string $exif_datatype
	 *
	 * @return bool
	 */
	public function set_exif_datatype( $exif_datatype ) {
		$this->exif_datatype = $exif_datatype;

		return true;
	}

	/**
	 * @param array $image_meta Exif Meta Data
	 *
	 * @return string|false
	 */
	public function format( $image_meta ) {
		if ( ! isset( $image_meta[ $this->get_exif_datatype() ] ) ) {
			return false;
		}

		$value = $image_meta[ $this->get_exif_datatype() ];

		switch ( $this->get_exif_datatype() ) {

			case 'created_timestamp' :
				$value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $value ) );
				break;
		}

		return $value;
	}

}