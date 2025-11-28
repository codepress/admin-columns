<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ApplyFilter\ColumnValue;
use AC\ApplyFilter\ColumnValueSanitize;
use AC\ListScreen;
use AC\Sanitize\Kses;
use AC\Setting\Context;
use AC\Setting\FormatterCollection;
use AC\Table\ProcessFormatters;
use AC\Type\Value;

class ColumnFormatter
{

    private FormatterCollection $formatters;

    private Context $context;

    private ?string $default;

    private ListScreen $list_screen;

    public function __construct(
        FormatterCollection $formatters,
        Context $context,
        ListScreen $list_screen,
        ?string $default = null
    ) {
        $this->formatters = $formatters;
        $this->context = $context;
        $this->default = $default;
        $this->list_screen = $list_screen;
    }

    public function format(Value $value): Value
    {
        if ($this->formatters->count() > 0) {
            $formatter = new ProcessFormatters($this->formatters, $this->default);

            $value = $formatter->format($value);

            $value = $this->sanitize_value(
                $value,
                $this->context,
                $value->get_id()
            );
        }

        $filter = new ColumnValue($this->context, $value->get_id(), $this->list_screen);

        return $filter->apply_filter($value);
    }

    private function use_sanitize(Context $context, $id): bool
    {
        return (new ColumnValueSanitize($context, $id, $this->list_screen))->apply_filter();
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