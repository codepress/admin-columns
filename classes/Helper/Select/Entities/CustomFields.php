<?php

namespace AC\Helper\Select\Entities;

use ACP\Helper\Select;
use ACP\Helper\Select\Value;
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
			$value = new Value\Post();
		}

		$query = new \AC\Meta\Query( 'post' );

		$query->select( 'meta_key' )
		      ->distinct()
		      ->order_by( 'meta_key' );

		if ( $args['post_type'] ) {
			$query->where_post_type( $args['post_type'] );
		}

		$keys = $query->get();

		if ( empty( $keys ) ) {
			$keys = false;
		}

		/**
		 * @param array                       $keys Distinct meta keys from DB
		 * @param Settings\Column\CustomField $this
		 */
		return apply_filters( 'ac/column/custom_field/meta_keys', $keys, $this );

		parent::__construct( $this->query->get_posts(), $value );
	}

	/**
	 * @inheritDoc
	 */
	public function get_total_pages() {
		$per_page = $this->query->get( 'posts_per_page' );

		return ceil( $this->query->found_posts / $per_page );
	}

	/**
	 * @inheritDoc
	 */
	public function get_page() {
		return $this->query->get( 'paged' );
	}

	/**
	 * @inheritDoc
	 */
	public function is_last_page() {
		return $this->get_total_pages() <= $this->get_page();
	}

}