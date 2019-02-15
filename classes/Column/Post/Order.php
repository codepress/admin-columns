<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * @since 2.0
 */
class Order extends Column {

	public function __construct() {
		$this->set_type( 'column-order' );
		$this->set_label( __( 'Order', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return is_post_type_hierarchical( $this->get_post_type() ) || post_type_supports( $this->get_post_type(), 'page-attributes' );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'menu_order', $post_id );
	}

}