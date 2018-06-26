<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * Column displaying path (without URL, e.g. "/my-category/sample-post/") to the front-end location of this item.
 *
 * @since 2.2.3
 */
class Path extends Column {

	public function __construct() {
		$this->set_type( 'column-path' );
		$this->set_label( __( 'Path', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return str_replace( home_url(), '', get_permalink( $post_id ) );
	}

}