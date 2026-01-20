<?php

namespace AC\Plugin\Update;

use AC\Column\ColumnIdGenerator;
use AC\Plugin\Update;
use AC\Plugin\Version;

class V7004 extends Update
{

    private ColumnIdGenerator $generator;

    public function __construct(ColumnIdGenerator $generator)
    {
        parent::__construct(new Version('7.0.4'));

        $this->generator = $generator;
    }

    public function apply_update(): void
    {
        // just in case we need a bit of extra time to execute our upgrade script
        if (ini_get('max_execution_time') < 120) {
            @set_time_limit(120);
        }
        
        $this->update_database_column_name();
    }

    private function update_database_column_name(): void
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT id, list_key, columns 
            FROM {$wpdb->prefix}admin_columns"
        );

        if ( ! $results) {
            return;
        }

        foreach ($results as $row) {
            $columns = $row->columns
                ? unserialize($row->columns, ['allowed_classes' => false])
                : [];

            if ( ! $columns || ! is_array($columns)) {
                continue;
            }

            $original_column_names = $this->get_original_column_names($row->list_key);

            $update = false;

            foreach ($columns as $key => $settings) {
                // Fix 1: missing 'name' argument for an original column should always be its 'type'
                if ( ! isset($settings['name']) && in_array($settings['type'], $original_column_names, true)) {
                    $columns[$key]['name'] = $settings['type'];

                    $update = true;
                    continue;
                }

                // Fix 2: 'name' argument must be present. use 'key' as 'name'.
                if ( ! isset($settings['name'])) {
                    $columns[$key]['name'] = is_numeric($key) && ((int)$key) < 10000
                        // make sure its not a valid generated ID which should be larger than 10000
                        ? (string)$this->generator->generate()
                        : $key;

                    $update = true;
                }
            }

            // Fix 3: the stored array should not be associative
            if ($this->is_associative_array($columns)) {
                // reset keys
                $columns = array_values($columns);
                $update = true;
            }

            if ($update) {
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$wpdb->prefix}admin_columns SET columns = %s WHERE id = %d",
                        serialize($columns),
                        $row->id
                    )
                );
            }
        }
    }

    private function is_associative_array(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function get_original_column_names(string $table_id): array
    {
        static $tables = [];

        if ( ! isset($tables[$table_id])) {
            $values = get_option('_ac_columns_default_' . $table_id);

            $tables[$table_id] = $values && is_array($values)
                ? array_keys($values)
                : [];
        }

        return $tables[$table_id];
    }

}