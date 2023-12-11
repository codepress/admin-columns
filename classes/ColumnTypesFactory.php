<?php

declare(strict_types=1);

namespace AC;

class ColumnTypesFactory
{

    public function create(TableScreen $table_screen): ColumnTypeCollection
    {
        $columns = (new DefaultColumnsRepository($table_screen->get_key()))->find_all();

        // TODO
        $columns_fqn = (array)apply_filters('ac/column_types_fqn', $table_screen->get_columns(), $table_screen);

        $collection = new ColumnTypeCollection();

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

            $collection->add($column);
        }

        do_action('ac/column_type_collection', $collection, $table_screen);

        return $collection;

        //  TODO check usages: do_action('ac/column_types', $this); and rename filter
        //        return apply_filters('ac/column_types', $collection, $table_screen);
    }

}