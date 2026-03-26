<?php

declare(strict_types=1);

namespace AC\Acf;

use AC\Acf\FieldGroup\Location;
use AC\Registerable;

/**
 * Provides cached ACF field counts per post type. The cache is stored as a
 * transient (1 week TTL) and invalidated automatically when ACF field groups
 * are created, updated, trashed, or restored.
 *
 * Counts are built lazily: a post type is only counted on first request,
 * then cached alongside any previously counted post types.
 */
class FieldCount implements Registerable
{

    private const TRANSIENT_KEY = '_ac_acf_field_counts';
    private const TTL_SECONDS = 604800;

    public function register(): void
    {
        add_action('save_post_acf-field-group', [$this, 'invalidate']);
    }

    public function get_count(string $post_type): int
    {
        if ( ! function_exists('acf_get_field_groups') || ! function_exists('acf_get_fields')) {
            return 0;
        }

        $counts = get_transient(self::TRANSIENT_KEY);

        if ( ! is_array($counts)) {
            $counts = [];
        }

        if (array_key_exists($post_type, $counts)) {
            return $counts[$post_type];
        }

        $count = $this->count_fields($post_type);
        $counts[$post_type] = $count;

        set_transient(self::TRANSIENT_KEY, $counts, self::TTL_SECONDS);

        return $count;
    }

    public function invalidate(): void
    {
        delete_transient(self::TRANSIENT_KEY);
    }

    private function count_fields(string $post_type): int
    {
        $location = new Location\Post($post_type);
        $count = 0;

        foreach ($location->get_groups() as $group) {
            $fields = acf_get_fields($group['key']);

            if (is_array($fields)) {
                $count += count($fields);
            }
        }

        return $count;
    }

}
