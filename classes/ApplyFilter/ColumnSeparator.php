<?php

namespace AC\ApplyFilter;

use AC\Column;

class ColumnSeparator
{

    private $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function apply_filters(string $value): string
    {
        return (string)apply_filters('ac/column/separator', $value, $this->column);
    }

}