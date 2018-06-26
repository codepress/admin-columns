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

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'title' );
	}

}