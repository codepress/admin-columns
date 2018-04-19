<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class Author extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'author' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 20 );
	}

}