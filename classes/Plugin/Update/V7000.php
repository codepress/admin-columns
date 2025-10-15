<?php

namespace AC\Plugin\Update;

use AC\Plugin\Install\Database;
use AC\Plugin\Update;
use AC\Plugin\Version;

class V7000 extends Update
{

    public const PROGRESS_KEY = 'ac_update_progress_v7000';

    private int $next_step;

    private Database $database;

    public function __construct(Database $database)
    {
        parent::__construct(new Version('7.0.0'));

        $this->database = $database;
        // because `get_option` could be cached we only fetch the next step from the DB on initialisation.
        $this->next_step = $this->get_next_step();
    }

    public function apply_update(): void
    {
        // just in case we need a bit of extra time to execute our upgrade script
        if (ini_get('max_execution_time') < 120) {
            @set_time_limit(120);
        }

        // Apply update in chunks to minimize the impact of a timeout.
        switch ($this->next_step) {
            case 1 :
                // Add 'type' column to the 'admin_columns' DB table
                $this->update_database();

                $this->update_next_step(2)
                     ->apply_update();
                break;
            case 2:
                // Update renamed column types and specific settings
                $this->update_columns();

                $this->update_next_step(3)
                     ->apply_update();
                break;
            case 3:
                // Move the stored default columns to a new location
                $this->update_default_columns();

                // Delete obsolete default sortables
                $this->delete_default_sortables();

                break;
        }
    }

    private function delete_default_sortables(): void
    {
        global $wpdb;

        $wpdb->query(
            "DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_sorting_%_default'"
        );
    }

    private function update_default_columns(): void
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT * FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%__default' AND option_value != ''"
        );

        if ( ! $results) {
            return;
        }

        foreach ($results as $item) {
            if ( ! isset($item->option_name)) {
                continue;
            }

            $list_key = ac_helper()->string->remove_prefix($item->option_name, 'cpac_options_');
            $list_key = ac_helper()->string->remove_suffix($list_key, '__default');

            if ( ! $list_key) {
                continue;
            }

            $data = unserialize($item->option_value, ['allowed_classes' => false]);

            if ( ! $data || ! is_array($data)) {
                continue;
            }

            $option_name = "ac_columns_default_" . $list_key;

            $exists = $wpdb->get_var(
                $wpdb->prepare("SELECT option_name FROM $wpdb->options WHERE option_name = %s", $option_name)
            );

            // Skip when exists
            if ($exists) {
                continue;
            }

            $updated = [];

            foreach ($data as $column_name => $label) {
                if ($column_name === 'cb') {
                    continue;
                }

                $updated[$column_name] = [
                    'label' => $label,
                ];
            }

            $wpdb->insert(
                $wpdb->options,
                [
                    'option_name' => $option_name,
                    'option_value' => $updated ? serialize($updated) : '',
                    'autoload' => 'off',
                ]
            );
        }

        // Delete
        $wpdb->query(
            "DELETE FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%__default'"
        );
    }

    private function update_columns(): void
    {
        global $wpdb;

        $views = $wpdb->get_results("SELECT id, list_id, columns FROM {$wpdb->prefix}admin_columns");

        if ( ! $views) {
            return;
        }

        $updates = [];

        foreach ($views as $view) {
            if ( ! $view->columns) {
                continue;
            }

            $columns = unserialize($view->columns, ['allowed_classes' => false]);

            if ( ! $columns || ! is_array($columns)) {
                continue;
            }

            $has_changed_columns = false;

            $columns = array_values($columns);

            foreach ($columns as $i => $column) {
                // invalid data
                if ( ! is_array($column) || ! $column) {
                    continue;
                }

                $updated_column = $this->modify_column_options($column);

                if ($updated_column) {
                    $columns[$i] = $updated_column;
                    $has_changed_columns = true;
                }
            }

            if ($has_changed_columns) {
                $updates[$view->id] = serialize($columns);
            }
        }

        foreach ($updates as $id => $columns) {
            $wpdb->query(
                $wpdb->prepare("UPDATE {$wpdb->prefix}admin_columns SET columns = %s WHERE ID = %d", $columns, $id)
            );
        }
    }

    private function modify_column_options(array $column): ?array
    {
        if ( ! isset($column['type'])) {
            return null;
        }

        // User column: `column-user_posts` has been replaced with `column-user_postcount`
        if ($column['type'] === 'column-user_posts') {
            $column['type'] = 'column-user_postcount';

            return $column;
        }

        // The column setting 'character_limit' has been renamed to 'excerpt_length'
        if ( ! empty($column['character_limit'])) {
            $column['excerpt_length'] = $column['character_limit'];
            unset($column['character_limit']);

            return $column;
        }

        // The column "Media ID (column-mediaid)" is replace by "Post ID" (column-postid)
        if ($column['type'] === 'column-mediaid') {
            $column['type'] = 'column-postid';

            return $column;
        }

        // The column gravatar has a new image size setting
        if ($column['type'] === 'column-gravatar') {
            $column['gravatar_size'] = $column['image_size_w'] ?? 60;

            unset($column['image_size'], $column['image_size_w'], $column['image_size_h']);

            return $column;
        }

        return null;
    }

    private function get_next_step(): int
    {
        return (int)get_option(self::PROGRESS_KEY, 1);
    }

    private function update_next_step(int $step): self
    {
        $this->next_step = $step;

        update_option(self::PROGRESS_KEY, $this->next_step, false);

        return $this;
    }

    private function update_database(): void
    {
        $this->database->install();
    }

}