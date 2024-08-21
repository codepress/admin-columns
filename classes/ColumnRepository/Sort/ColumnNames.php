<?php

declare(strict_types=1);

namespace AC\ColumnRepository\Sort;

use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository\Sort;

class ColumnNames implements Sort
{

    private $column_names;

    public function __construct(array $column_names)
    {
        $this->column_names = $column_names;
    }

    public function sort(ColumnIterator $columns): ColumnCollection
    {
        $ordered = $last = [];

        foreach ($columns as $column) {
            $key = array_search((string)$column->get_id(), $this->column_names);

            if (false === $key) {
                $last[] = $column;
                continue;
            }

            $ordered[$key] = $column;
        }

        ksort($ordered);

        return new ColumnCollection(array_merge($ordered, $last));
    }

}