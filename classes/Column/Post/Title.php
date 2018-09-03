<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * @since 3.0
 */
class Title extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'title' );
	}

}