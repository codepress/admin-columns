<?php

declare(strict_types=1);

namespace AC\ColumnRepository\Sort;

use AC\Column;
use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository\Sort;

class ColumnNames implements Sort
{
    private array $column_names;

    public function __construct(array $column_names)
    {
        $this->column_names = $column_names;
    }

    public function sort(ColumnIterator $columns): ColumnCollection
    {
        $columns = iterator_to_array($columns, false);
        $positions = array_flip(array_map('strval', array_values($this->column_names)));

        $ordered = [];

        foreach ($columns as $column) {
            $position = $positions[(string)$column->get_id()] ?? null;

            if (null !== $position) {
                $ordered[$position] = $column;
            }
        }

        ksort($ordered);
        $ordered = array_values($ordered);

        // A column missing from the given names follows the column it follows in the original order
        $anchor = null;
        $leading = [];

        foreach ($columns as $column) {
            if (isset($positions[(string)$column->get_id()])) {
                if (null === $anchor) {
                    array_splice($ordered, $this->index_of($ordered, $column), 0, $leading);
                }

                $anchor = $column;
                continue;
            }

            if (null === $anchor) {
                $leading[] = $column;
                continue;
            }

            array_splice($ordered, $this->index_of($ordered, $anchor) + 1, 0, [$column]);
            $anchor = $column;
        }

        return new ColumnCollection($anchor ? $ordered : $leading);
    }

    private function index_of(array $columns, Column $column): int
    {
        return (int)array_search($column, $columns, true);
    }

}
