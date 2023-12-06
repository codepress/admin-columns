<?php

namespace AC;

use AC\ColumnRepository\Filter;
use AC\ColumnRepository\Sort;

class ColumnListScreenRepository
{

    public const ARG_FILTER = 'filter';
    public const ARG_SORT = 'sort';

    private $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    public function find(string $column_name): ?Column
    {
        $columns = $this->list_screen->get_columns();

        return $columns->exists($column_name)
            ? $columns->get($column_name)
            : null;
    }

    public function find_all(array $args = []): ColumnCollection
    {
        $args = array_merge([
            self::ARG_SORT   => null,
            self::ARG_FILTER => [],
        ], $args);

        $columns = $this->list_screen->get_columns();

        // Deprecated usage
        if ($args[self::ARG_FILTER] instanceof Filter) {
            $args[self::ARG_FILTER] = [$args[self::ARG_FILTER]];
        }

        if ($args[self::ARG_FILTER]) {
            foreach ($args[self::ARG_FILTER] as $filter) {
                if ($filter instanceof Filter) {
                    $columns = $filter->filter($columns);
                }
            }
        }

        if ($args[self::ARG_SORT] instanceof Sort) {
            $columns = $args[self::ARG_SORT]->sort($columns);
        }

        return $columns;
    }

}