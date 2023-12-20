<?php

namespace AC\ColumnTypesFactory;

use AC\ColumnTypeCollection;

trait ColumnTypesTrait
{

    public function create_from_list(array $columns_fqn): ColumnTypeCollection
    {
        $collection = new ColumnTypeCollection();

        foreach ($columns_fqn as $column_fqn) {
            $collection->add(new $column_fqn());
        }

        return $collection;
    }

}