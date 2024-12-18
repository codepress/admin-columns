<?php

declare(strict_types=1);

namespace AC\ColumnRepository\Filter;

use AC\Column;
use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository\Filter;

class ColumnNames implements Filter
{

    private array $column_names;

    public function __construct(array $column_names)
    {
        $this->column_names = $column_names;
    }

    public function filter(ColumnIterator $columns): ColumnCollection
    {
        return new ColumnCollection(array_filter(iterator_to_array($columns), [$this, 'contains']));
    }

    private function contains(Column $column): bool
    {
        return in_array((string)$column->get_id(), $this->column_names, true);
    }

}