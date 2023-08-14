<?php

namespace AC\Helper;

class Menu
{

    public function get_menu_label(int $menu_item_id): string
    {
        global $wpdb;

        return (string)$wpdb->prepare(
            "
			SELECT t.name
				FROM $wpdb->terms AS t
				INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id
				INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
				INNER JOIN $wpdb->posts AS menu ON menu.ID = tr.object_id
				    AND menu.post_type = 'nav_menu_item'
    			WHERE menu.ID = %d
			",
            $menu_item_id
        );
    }

    /**
     * @param int    $object_id
     * @param string $object_type
     *
     * @return int[] Term Ids
     */
    public function get_ids($object_id, $object_type)
    {
        return get_posts([
            'post_type'      => 'nav_menu_item',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'   => '_menu_item_object_id',
                    'value' => (int)$object_id,
                ],
                [
                    'key'   => '_menu_item_object',
                    'value' => (string)$object_type,
                ],
            ],
        ]);
    }

    /**
     * @param array $terms_ids
     * @param array $args
     *
     * @return array
     * @see WP_Term_Query::__construct() for available $args
     */
    public function get_terms(array $terms_ids, array $args = [])
    {
        if ( ! $terms_ids) {
            return [];
        }

        $terms = wp_get_object_terms($terms_ids, 'nav_menu', $args);

        if ( ! $terms || is_wp_error($terms)) {
            return [];
        }

        return $terms;
    }

}