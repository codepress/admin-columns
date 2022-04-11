<?php

namespace AC\Column\Media;

use AC\Column;
use AC\View;

class DetailedExifData extends Column\Media\MetaValue
	implements Column\DetailedValue {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-detailed_exif_data' )
		     ->set_label( __( 'Detailed EXIF Data', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return sprintf( '<a data-modal-value href="#">%s</a>', __( 'View', 'codepress-admin-columns' ) );
	}

	public function get_modal_value( $id ) {
		// TODO TEST
		$view = new View( [
			'title'     => get_the_title( $id ),
			'file_name' => get_post_meta( $id, '_wp_attached_file', true ),
			'file_url'  => wp_get_attachment_url( $id ),
			'exif_data' => $this->map_exif_data( $this->get_raw_value( $id ) ),
		] );

		return $view->set_template( 'column/value/exif' )
		            ->render();
	}

	private function map_exif_data( $exif_data ) {
		$data = [];
		foreach ( $exif_data as $key => $value ) {
			if ( ! $value ) {
				continue;
			}

			$data[ $this->get_exif_label( $key ) ] = $value;
		}

		return $data;
	}

	private function get_exif_label( $key ) {
		$labels = $this->exif_key_labels();

		return array_key_exists( $key, $labels ) ? $labels[ $key ] : $key;
	}

	private function exif_key_labels() {
		return [
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
		];
	}

	protected function get_option_name() {
		return 'image_meta';
	}

}