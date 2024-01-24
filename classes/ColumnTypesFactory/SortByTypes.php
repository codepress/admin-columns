<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC\ColumnTypeCollection;

class SortByTypes
{

    private $column_types;

    public function __construct(array $column_types)
    {
        $this->column_types = $column_types;
    }

    public function sort(ColumnTypeCollection $collection): void
    {
        $ordered = [];

        foreach ($collection as $column) {
            $key = array_search($column->get_type(), $this->column_types);

            if (false === $key) {
                continue;
            }

            $ordered[$key] = $column;
        }

        ksort($ordered);

        foreach ($ordered as $column) {
            $collection->remove($column);
        }

        foreach ($ordered as $column) {
            $collection->add($column);
        }
    }

}