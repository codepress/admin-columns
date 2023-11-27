<?php

declare(strict_types=1);

namespace AC\ColumnRepository\Sort;

use AC\ColumnRepository\Sort;

class ColumnNames implements Sort
{

    private $column_names;

    public function __construct(array $column_names)
    {
        $this->column_names = $column_names;
    }

    public function sort(array $columns): array
    {
        $ordered = $last = [];

        foreach ($columns as $column) {
            $key = array_search($column->get_name(), $this->column_names);

            if (false === $key) {
                $last[] = $column;
                continue;
            }

            $ordered[$key] = $column;
        }

        ksort($ordered);

        return array_merge($ordered, $last);
    }

}