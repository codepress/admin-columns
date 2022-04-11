<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

abstract class FileMeta extends Column\Meta {

	public function get_meta_key() {
		return '_wp_attachment_metadata';
	}

	/**
	 * @return array
	 */
	public function get_sub_keys() {
		$setting = $this->get_setting( 'media_meta' );

		if ( ! $setting instanceof Settings\Column\MediaMeta ) {
			return null;
		}

		return $setting->get_media_meta_keys();
	}

	public function get_raw_value( $id ) {
		$value = $this->get_meta_value( $id, $this->get_meta_key() );

		$keys = $this->get_sub_keys();

		if ( isset( $keys[0] ) && isset( $value[ $keys[0] ] ) ) {
			$value = $value[ $keys[0] ];
		}

		if ( isset( $keys[1] ) && isset( $value[ $keys[1] ] ) ) {
			$value = $value[ $keys[1] ];
		}

		return is_scalar( $value )
			? $value
			: null;
	}

}