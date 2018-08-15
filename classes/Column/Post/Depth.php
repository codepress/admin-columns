<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * Depth of the current page (number of ancestors + 1)
 * @since 2.3.4
 */
class Depth extends Column {

	public function __construct() {
		$this->set_type( 'column-depth' );
		$this->set_label( __( 'Depth', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return count( get_post_ancestors( $post_id ) ) + 1;
	}

	public function is_valid() {
		return is_post_type_hierarchical( $this->get_post_type() );
	}

}