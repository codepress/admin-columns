<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ApplyFilter\ColumnValue;
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

    private ?string $default;

    public function __construct(FormatterCollection $formatters, Context $context, string $default = null)
    {
        $this->formatters = $formatters;
        $this->context = $context;
        $this->default = $default;
    }

    public function render($row_id): ?string
    {
        $value = null;

        if ($this->formatters->count() > 0) {
            $formatter = new ProcessFormatters($this->formatters, $this->default);

            $value = (string)$this->sanitize_value(
                $formatter->format(new Value($row_id)),
                $this->context,
                $row_id
            );
        }

        return (new ColumnValue($this->context, $row_id))->apply_filter($value);
    }

    private function use_sanitize(Context $context, $id): bool
    {
        return (new ColumnValueSanitize($context, $id))->apply_filter();
    }

    private function sanitize_value(Value $value, Context $context, $id): Value
    {
        if ( ! $this->use_sanitize($context, $id)) {
            return $value;
        }

        return $value->with_value(
            (new Kses())->sanitize((string)$value)
        );
    }

}