<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class Comment extends Column {

	public function __construct() {
		$this->set_original( true )
		     ->set_type( 'comment' );
	}

}