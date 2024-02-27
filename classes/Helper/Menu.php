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

    public function get_ids(int $object_id, string $object_type): array
    {
        return get_posts([
            'post_type'      => 'nav_menu_item',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'   => '_menu_item_object_id',
                    'value' => $object_id,
                ],
                [
                    'key'   => '_menu_item_object',
                    'value' => $object_type,
                ],
            ],
        ]);
    }

    /**
     * @see WP_Term_Query::__construct() for supported arguments.
     */
    public function get_terms(array $terms_ids, array $args = []): array
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