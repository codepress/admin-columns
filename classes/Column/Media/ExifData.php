<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class ExifData extends Column\Media\MetaValue {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-exif_data' )
		     ->set_group( 'media-meta' )
		     ->set_label( __( 'Image Data (EXIF)', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'image_meta';
	}

	public function is_valid() {
		return function_exists( 'exif_read_data' );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\ExifData( $this ) );
	}

}