<?php

declare(strict_types=1);

namespace AC;

class ColumnTypesFactory
{

    /**
     * @param TableScreen $table_screen
     *
     * @return Column[]
     */
    public function create(TableScreen $table_screen): array
    {
        $columns = (new DefaultColumnsRepository($table_screen->get_key()))->find_all();

        // TODO
        $columns_fqn = (array)apply_filters('ac/column_types_fqn', $table_screen->get_columns(), $table_screen);

        foreach ($columns_fqn as $column_fqn) {
            /**
             * @var Column $columnn
             */
            $column = new $column_fqn();

            $original = $columns[$column->get_type()] ?? null;

            // skip original column types that do not exist
            if ( ! $original && $column->is_original()) {
                continue;
            }

            if ($original) {
                $column->set_label($original->get_label())
                       ->set_group($original->get_group());
            }

            $columns[$column->get_type()] = $column;
        }

        $columns = array_values($columns);

        //  TODO check usages: do_action('ac/column_types', $this);
        return (array)apply_filters('ac/column_types', $columns, $table_screen);
    }

}