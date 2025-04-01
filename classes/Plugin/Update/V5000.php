<?php

namespace AC\Plugin\Update;

use AC\Plugin\Install\Database;
use AC\Plugin\Update;
use AC\Plugin\Version;

// TODO add update logic and register class
class V5000 extends Update
{

    public const PROGRESS_KEY = 'ac_update_progress_v5000';

    private int $next_step;

    private Database $database;

    public function __construct(Database $database)
    {
        parent::__construct(new Version('5.0.0'));

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
                $this->update_database();

                $this->update_next_step(2)
                     ->apply_update();
                break;
            case 2:
                $this->update_columns();

                $this->update_next_step(3)
                     ->apply_update();
        }
    }

    private function update_columns(): void
    {
        global $wpdb;

        $views = $wpdb->get_results("SELECT id, list_id, columns FROM {$wpdb->prefix}admin_columns");

        $updates = [];

        foreach ($views as $view) {
            if ( ! $view->columns) {
                continue;
            }

            $has_changed_columns = false;
            $columns = unserialize($view->columns, ['allowed_classes' => false]);
            $columns = array_values($columns);

            foreach ($columns as $i => $column) {
                // User column: `column-user_posts` has been replaced with `column-user_postcount`
                if ($column['type'] === 'column-user_posts') {
                    $has_changed_columns = true;
                    $columns[$i]['type'] = 'column-user_postcount';
                }

                // The column setting 'character_limit' has been renamed to 'excerpt_length'
                if ( ! empty($column['character_limit'])) {
                    $has_changed_columns = true;
                    $columns[$i]['excerpt_length'] = $column['character_limit'];
                    unset($columns[$i]['character_limit']);
                }

                // The column "Media ID (column-mediaid)" is replace by "Post ID" (column-postid)
                // Delete from options table 'cpac_options_%s__default'
                // Delete from options table 'ac_sorting_%s_default'
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