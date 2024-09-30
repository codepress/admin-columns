<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ApplyFilter\ColumnValueSanitize;
use AC\Column;
use AC\Sanitize\Kses;
use AC\Type\Value;

class ColumnRenderable
{

    private Column $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function render(int $id): ?string
    {
        $formatters = $this->column->get_formatters();

        // TODO Test, how to bail on original columns? a column always need to have a formatter?
        if ($formatters->count() === 0) {
            return null;
        }

        $formatter = (new ProcessFormatters($formatters));

        $value = $formatter->format(
            new Value($id)
        );

        $value = (string)$this->sanitize_value($value, $this->column, $id);

        // TODO rename filter
        return (string)apply_filters('ac/column/value', $value, $id, $this->column);
    }

    private function use_sanitize(Column $column, int $id): bool
    {
        return (new ColumnValueSanitize($column, $id))->apply_filter();
    }

    private function sanitize_value(Value $value, Column $column, int $id): Value
    {
        if ( ! $this->use_sanitize($column, $id)) {
            return $value;
        }

        return $value->with_value(
            (new Kses())->sanitize((string)$value)
        );
    }

}