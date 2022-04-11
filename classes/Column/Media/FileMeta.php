<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

abstract class FileMeta extends Column\Meta {

	public function get_meta_key() {
		return '_wp_attachment_metadata';
	}

	/**
	 * @return Settings\Column\FileMeta
	 */
	protected function get_media_setting() {
		$setting = $this->get_setting( 'media_meta' );

		return $setting instanceof Settings\Column\FileMeta
			? $setting
			: null;
	}

	/**
	 * @return array
	 */
	public function get_sub_keys() {
		return $this->get_media_setting()->get_media_meta_keys();
	}

	protected function get_metadata_value( array $data, array $keys ) {
		if ( isset( $keys[0] ) && isset( $data[ $keys[0] ] ) ) {
			$data = $data[ $keys[0] ];
		}

		if ( isset( $keys[1] ) && isset( $data[ $keys[1] ] ) ) {
			$data = $data[ $keys[1] ];
		}

		return is_scalar( $data )
			? $data
			: null;
	}

	public function get_raw_value( $id ) {
		$data = $this->get_meta_value( $id, $this->get_meta_key() );

		return is_array( $data )
			? $this->get_metadata_value( $data, $this->get_sub_keys() )
			: null;
	}

}