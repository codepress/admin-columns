<?php

namespace AC\Column\Post;

use AC\Column;

// TODO remove. all originals can be removed.
class Tags extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'tags' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}