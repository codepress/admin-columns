<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

class SortByTypes
{

    private $column_types;

    public function __construct(array $column_types)
    {
        $this->column_types = $column_types;
    }

    public function sort(array $columns): array
    {
        $ordered = $last = [];

        foreach ($columns as $type => $column) {
            $key = array_search($type, $this->column_types);

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