<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * Depth of the current page (number of ancestors + 1)
 */
class Depth extends Column {

	public function __construct() {
		$this->set_type( 'column-depth' )->set_label( __( 'Depth', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return count( get_post_ancestors( $post_id ) ) + 1;
	}

}