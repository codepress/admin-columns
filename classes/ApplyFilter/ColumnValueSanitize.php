<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Column;

class ColumnValueSanitize
{

    private Column $column;

    private $id;

    public function __construct(Column $column, $id)
    {
        $this->column = $column;
        $this->id = $id;
    }

    public function apply_filter(bool $sanitize = true): bool
    {
        return (bool)apply_filters('ac/column/value/sanitize', $sanitize, $this->column, $this->id);
    }

}