<?php

declare(strict_types=1);

namespace AC\Table\Renderable;

use AC\Column;
use AC\Sanitize\Kses;
use AC\Setting\FormatterCollection;
use AC\Table\ProcessFormatters;
use AC\Table\Renderable;
use AC\Type\Value;

// TODO check usages
class ColumnRenderable implements Renderable
{

    private FormatterCollection $formatters;

    public function __construct(FormatterCollection $formatters)
    {
        $this->formatters = $formatters;
    }

    public function render($row_id): ?string
    {
        $formatter = (new ProcessFormatters($this->formatters));

        return (string)$formatter->format(new Value($row_id));

        // TODO implement sanitize
        //$value = (string)self::sanitize_value($value, $this->column, $row_id);

        // TODO implement apply_filters
        //        return (string)apply_filters('ac/column/value', $value, $row_id, $this->column);
    }

    private function use_sanitize(Column $column, int $id): bool
    {
        return true;
        // TODO
        //        return (new ColumnValueSanitize($column, $id))->apply_filter();
    }

    private function sanitize_value(Value $value, Column $column, int $id): Value
    {
        if ( ! self::use_sanitize($column, $id)) {
            return $value;
        }

        return $value->with_value(
            (new Kses())->sanitize((string)$value)
        );
    }

}