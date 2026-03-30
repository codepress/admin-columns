<?php

declare(strict_types=1);

namespace AC\Acf;

use AC\Acf\FieldGroup\Location;
use AC\Acf\FieldGroup\Query;
use AC\PostType;
use AC\Registerable;
use AC\TableScreen;

/**
 * Provides cached ACF field counts. The cache is stored as a transient
 * (1 week TTL) and invalidated automatically when ACF field groups are
 * created, updated, trashed, or restored.
 * Counts are built lazily: a key is only counted on first request,
 * then cached alongside any previously counted keys.
 */
class FieldCount implements Registerable
{

    private const TRANSIENT_KEY = '_ac_acf_field_counts';
    private const TTL_SECONDS = WEEK_IN_SECONDS;

    public function register(): void
    {
        add_action('save_post_acf-field-group', [$this, 'invalidate']);
        add_action('trashed_post', [$this, 'invalidate_field_group']);
        add_action('untrashed_post', [$this, 'invalidate_field_group']);
    }

    public function invalidate_field_group(int $post_id): void
    {
        if ('acf-field-group' === get_post_type($post_id)) {
            $this->invalidate();
        }
    }

    public function get_count_for_post_type(string $post_type): int
    {
        return $this->get_count_for_query($post_type, new Location\Post($post_type));
    }

    public function get_count_for_table_screen(TableScreen $table_screen): int
    {
        $query = $this->create_query($table_screen);

        if ( ! $query) {
            return 0;
        }

        return $this->get_count_for_query((string)$table_screen->get_id(), $query);
    }

    private function create_query(TableScreen $table_screen): ?Query
    {
        if ($table_screen instanceof TableScreen\Media) {
            return new Location\Media();
        }

        if ($table_screen instanceof TableScreen\Comment) {
            return new Location\Comment();
        }

        if ($table_screen instanceof PostType) {
            return new Location\Post((string)$table_screen->get_post_type());
        }

        if ($table_screen instanceof TableScreen\User) {
            return new Location\User();
        }

        return null;
    }

    private function get_count_for_query(string $cache_key, Query $query): int
    {
        if ( ! function_exists('acf_get_field_groups') || ! function_exists('acf_get_fields')) {
            return 0;
        }

        $counts = get_transient(self::TRANSIENT_KEY);

        if ( ! is_array($counts)) {
            $counts = [];
        }

        if (array_key_exists($cache_key, $counts)) {
            return $counts[$cache_key];
        }

        $count = $this->count_fields($query);
        $counts[$cache_key] = $count;

        set_transient(self::TRANSIENT_KEY, $counts, self::TTL_SECONDS);

        return $count;
    }

    public function invalidate(): void
    {
        delete_transient(self::TRANSIENT_KEY);
    }

    private function count_fields(Query $query): int
    {
        $count = 0;

        foreach ($query->get_groups() as $group) {
            $fields = acf_get_fields($group['key']);

            if (is_array($fields)) {
                $count += count($fields);
            }
        }

        return $count;
    }

}
