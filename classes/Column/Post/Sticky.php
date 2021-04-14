<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * @since 2.0
 */
class Sticky extends Column {

	private $stickies;

	public function __construct() {
		$this->set_type( 'column-sticky' );
		$this->set_label( __( 'Sticky', 'codepress-admin-columns' ) );
	}

	function is_valid() {
		return 'post' === $this->get_post_type();
	}

	function get_value( $post_id ) {
		return ac_helper()->icon->yes_or_no( $this->is_sticky( $post_id ) );
	}

	function get_raw_value( $post_id ) {
		return $this->is_sticky( $post_id );
	}

	// Helpers
	private function is_sticky( $post_id ) {
		if ( null === $this->stickies ) {
			$this->stickies = get_option( 'sticky_posts' );
		}

		return in_array( $post_id, (array) $this->stickies );
	}

}