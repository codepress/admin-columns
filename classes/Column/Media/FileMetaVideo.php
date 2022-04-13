<?php

namespace AC\Column\Media;

use AC\Settings;

class FileMetaVideo extends FileMeta {

	public function __construct() {
		$this->set_type( 'column-meta_video' )
		     ->set_group( 'media-video' )
		     ->set_label( __( 'Video Meta', 'codepress-admin-columns' ) );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\FileMetaVideo( $this ) );
	}

}