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
        $ordered = [];

        foreach ($this->column_types as $type) {
            if ( ! isset($columns[$type])) {
                continue;
            }

            $ordered[] = $columns[$type];

            unset($columns[$type]);
        }

        return array_merge(
            array_values($columns), // rebase keys
            $ordered
        );
    }

}