<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ApplyFilter\ColumnValueSanitize;
use AC\Sanitize\Kses;
use AC\Setting\Context;
use AC\Setting\FormatterCollection;
use AC\Table\ProcessFormatters;
use AC\Type\Value;

class ColumnRenderable
{

    private FormatterCollection $formatters;

    private Context $context;

    public function __construct(FormatterCollection $formatters, Context $context)
    {
        $this->formatters = $formatters;
        $this->context = $context;
    }

    public function render($row_id): ?string
    {
        $formatter = (new ProcessFormatters($this->formatters));

        $value = $formatter->format(new Value($row_id));

        $value = (string)self::sanitize_value($value, $this->context, $row_id);

        return (string)apply_filters('ac/v2/column/value', $value, $row_id, $this->context);
    }

    private function use_sanitize(Context $context, int $id): bool
    {
        return (new ColumnValueSanitize($context, $id))->apply_filter();
    }

    private function sanitize_value(Value $value, Context $context, int $id): Value
    {
        if ( ! self::use_sanitize($context, $id)) {
            return $value;
        }

        return $value->with_value(
            (new Kses())->sanitize((string)$value)
        );
    }

}