<?php

namespace AC\Column\Media;

use AC\Settings;

class FileMetaVideo extends FileMeta {

	public function __construct() {
		$this->set_type( 'column-meta_video' )
		     ->set_group( 'media-meta' )
		     ->set_label( __( 'Video Data', 'codepress-admin-columns' ) );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\Media\VideoMeta( $this ) );
	}

}