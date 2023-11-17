<?php

namespace AC\Column\Post;

use AC\Column;

class Categories extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'categories' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

}