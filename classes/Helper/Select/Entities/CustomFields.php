<?php

namespace AC\Helper\Select\Entities;

use AC;
use AC\Helper\Select;
use AC\Helper\Select\UnqiueValueFormatter;

class CustomFields extends Select\Entities
	implements Select\Paginated {

	/**
	 * @param array                $args
	 * @param UnqiueValueFormatter $value
	 */
	public function __construct( array $args = [], UnqiueValueFormatter $value = null ) {
		if ( null === $value ) {
			$value = new Value\NullFormatter();
		}

		$args = array_merge( [
			'meta_type' => 'post',
			'post_type' => false,
		], $args );

		$query = new AC\Meta\Query( $args['meta_type'] );

		$query->select( 'meta_key' )
		      ->distinct()
		      ->order_by( 'meta_key' );

		if ( $args['post_type'] ) {
			$query->where_post_type( $args['post_type'] );
		}

		parent::__construct( $query->get(), $value );
	}

	public function get_total_pages() {
		return 1;
	}

	public function get_page() {
		return 1;
	}

	public function is_last_page() {
		return true;
	}

}