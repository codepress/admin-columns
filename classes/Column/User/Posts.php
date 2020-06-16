<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 3.0
 */
class Posts extends Column {

	public function __construct() {
		$this->set_original( true )
		     ->set_type( 'posts' );
	}

}