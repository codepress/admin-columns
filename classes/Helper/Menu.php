<?php

namespace AC\Helper;

class Menu {

	/**
	 * @param int    $object_id
	 * @param string $object_type
	 *
	 * @return int[] Term Ids
	 */
	public function get_ids( $object_id, $object_type ) {
		return get_posts( [
			'post_type'      => 'nav_menu_item',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'   => '_menu_item_object_id',
					'value' => (int) $object_id,
				],
				[
					'key'   => '_menu_item_object',
					'value' => (string) $object_type,
				],
			],
		] );
	}

	/**
	 * @param array $terms_ids
	 * @param array $args
	 *
	 * @return array
	 * @see WP_Term_Query::__construct() for available $args
	 */
	public function get_terms( array $terms_ids, array $args = [] ) {
		if ( ! $terms_ids ) {
			return [];
		}

		$terms = wp_get_object_terms( $terms_ids, 'nav_menu', $args );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return [];
		}

		return $terms;
	}

}