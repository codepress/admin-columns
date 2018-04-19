<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 3.0
 */
class MediaParent extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'parent' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}