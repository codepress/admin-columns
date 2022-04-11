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