<?php

declare(strict_types=1);

namespace AC\Acf;

use AC\Acf\FieldGroup\Location;
use AC\Acf\FieldGroup\Query;
use AC\Acf\FieldGroup\QueryFactory;
use AC\Registerable;
use AC\TableIdsFactory;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\TableId;

/**
 * Central cache for ACF field group data: field counts per table screen
 * and the group-to-table-screen mapping. Stored as transients (1 week TTL)
 * and invalidated automatically when ACF field groups are created, updated,
 * trashed, or restored.
 */
class FieldGroupCache implements Registerable
{

    private const TRANSIENT_KEY = '_ac_acf_field_counts';
    private const TRANSIENT_KEY_TABLE_SCREENS = '_ac_acf_group_table_screens';
    private const TTL_SECONDS = WEEK_IN_SECONDS;

    private QueryFactory $query_factory;

    private TableIdsFactory $table_ids_factory;

    private TableScreenFactory $table_screen_factory;

    public function __construct(
        QueryFactory $query_factory,
        TableIdsFactory $table_ids_factory,
        TableScreenFactory $table_screen_factory
    ) {
        $this->query_factory = $query_factory;
        $this->table_ids_factory = $table_ids_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function register(): void
    {
        add_action('save_post_acf-field-group', [$this, 'invalidate']);
        add_action('trashed_post', [$this, 'invalidate_field_group']);
        add_action('untrashed_post', [$this, 'invalidate_field_group']);
        add_action('deleted_post', [$this, 'invalidate_field_group']);
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

    /**
     * @return TableScreen[]
     */
    public function get_table_screens_for_group(int $id): array
    {
        $map = $this->get_group_table_screen_map();

        if ( ! isset($map[$id])) {
            return [];
        }

        $table_screens = [];

        foreach ($map[$id] as $table_id) {
            $table_id = new TableId($table_id);

            if ($this->table_screen_factory->can_create($table_id)) {
                $table_screens[] = $this->table_screen_factory->create($table_id);
            }
        }

        return $table_screens;
    }

    /**
     * @return array<int, string[]> Group ID => list of table ID strings
     */
    private function get_group_table_screen_map(): array
    {
        $map = get_transient(self::TRANSIENT_KEY_TABLE_SCREENS);

        if (is_array($map)) {
            return $map;
        }

        if ( ! $this->is_acf_available()) {
            return [];
        }

        $map = [];

        foreach ($this->table_ids_factory->create() as $table_id) {
            if ( ! $this->table_screen_factory->can_create($table_id)) {
                continue;
            }

            $table_screen = $this->table_screen_factory->create($table_id);
            $query = $this->query_factory->create($table_screen);

            if ( ! $query) {
                continue;
            }

            foreach ($query->get_groups() as $group) {
                $group_id = (int)($group['ID'] ?? 0);

                if ($group_id <= 0) {
                    continue;
                }

                if ( ! isset($map[$group_id])) {
                    $map[$group_id] = [];
                }

                $table_id_string = (string)$table_id;

                if ( ! in_array($table_id_string, $map[$group_id], true)) {
                    $map[$group_id][] = $table_id_string;
                }
            }
        }

        set_transient(self::TRANSIENT_KEY_TABLE_SCREENS, $map, self::TTL_SECONDS);

        return $map;
    }

    public function get_count_for_table_screen(TableScreen $table_screen): int
    {
        $query = $this->query_factory->create($table_screen);

        if ( ! $query) {
            return 0;
        }

        return $this->get_count_for_query((string)$table_screen->get_id(), $query);
    }

    private function is_acf_available(): bool
    {
        return function_exists('acf_get_field_groups')
            && function_exists('acf_get_fields')
            && function_exists('acf_get_store');
    }

    private function get_count_for_query(string $cache_key, Query $query): int
    {
        if ( ! $this->is_acf_available()) {
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
        delete_transient(self::TRANSIENT_KEY_TABLE_SCREENS);
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
