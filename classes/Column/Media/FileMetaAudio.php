<?php

namespace AC\Column\Media;

use AC\Settings;

class FileMetaAudio extends FileMeta {

	public function __construct() {
		$this->set_type( 'column-meta_audio' )
		     ->set_group( 'media-meta' )
		     ->set_label( __( 'Audio Data', 'codepress-admin-columns' ) );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\Media\AudioMeta( $this ) );
	}

}