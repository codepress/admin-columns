<?php

namespace AC\Column\Post;

use AC\Column;

class Comments extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'comments' );
	}

}