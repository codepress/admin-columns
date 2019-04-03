<?php

namespace AC\Helper\Select\Entities;

use AC\Helper\Select;
use AC\Helper\Select\Value;
use WP_Query;

class CustomFields extends Select\Entities
	implements Select\Paginated {

	/**
	 * @var WP_Query
	 */
	protected $query;

	/**
	 * @param array $args
	 * @param Value $value
	 */
	public function __construct( array $args = array(), Value $value = null ) {
		if ( null === $value ) {
			$value = new Value\Copy();
		}

		$args = array_merge( array(
			'meta_type' => 'post',
			'post_type' => false,
		), $args );

		$query = new \AC\Meta\Query( $args['meta_type'] );

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
		return $this->get_total_pages() <= $this->get_page();
	}

}