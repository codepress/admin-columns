<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class ExifData extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $exif_datatype;

	protected function set_name() {
		$this->name = 'exif_data';
	}

	protected function define_options() {
		return array( 'exif_datatype' => 'aperture' );
	}

	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_attribute( 'data-label', 'update' )
		                ->set_attribute( 'data-refresh', 'column' )
		                ->set_options( $this->get_exif_types() );

		return new View( array(
			'label'   => $this->column->get_label(),
			'setting' => $setting,
		) );
	}

	public function get_dependent_settings() {

		switch ( $this->get_exif_datatype() ) {
			case 'aperture' :
				$settings = array( new Settings\Column\BeforeAfter\Aperture( $this->column ) );

				break;
			case 'focal_length' :
				$settings = array( new Settings\Column\BeforeAfter\FocalLength( $this->column ) );

				break;
			case 'iso' :
				$settings = array( new Settings\Column\BeforeAfter\ISO( $this->column ) );

				break;
			case 'shutter_speed' :
				$settings = array( new Settings\Column\BeforeAfter\ShutterSpeed( $this->column ) );

				break;
			default :
				$settings = array( new Settings\Column\BeforeAfter( $this->column ) );
		}

		return $settings;
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
			'copyright'         => __( 'Copyright', 'codepress-admin-columns' ),
			'focal_length'      => __( 'Focal Length', 'codepress-admin-columns' ),
			'iso'               => __( 'ISO', 'codepress-admin-columns' ),
			'shutter_speed'     => __( 'Shutter Speed', 'codepress-admin-columns' ),
			'title'             => __( 'Title', 'codepress-admin-columns' ),
			'orientation'       => __( 'Orientation', 'codepress-admin-columns' ),
			'keywords'          => __( 'Keywords', 'codepress-admin-columns' ),
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

	public function format( $value, $original_value ) {
		$exif_datatype = $this->get_exif_datatype();
		$value = isset( $value[ $exif_datatype ] ) ? $value[ $exif_datatype ] : '';

		if ( false != $value ) {
			switch ( $exif_datatype ) {
				case 'created_timestamp' :
					$value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $value );

					break;
				case 'keywords' :
					$value = ac_helper()->array->implode_recursive( ', ', $value );

					break;
			}
		}

		return $value;
	}

}