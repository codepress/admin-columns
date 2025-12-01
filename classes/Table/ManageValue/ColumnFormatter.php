<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ApplyFilter\ColumnValue;
use AC\ApplyFilter\ColumnValueSanitize;
use AC\Sanitize\Kses;
use AC\Setting\Context;
use AC\Setting\FormatterCollection;
use AC\Table\ProcessFormatters;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;

class ColumnFormatter
{

    private FormatterCollection $formatters;

    private Context $context;

    private ?string $default;

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    public function __construct(
        FormatterCollection $formatters,
        Context $context,
        TableScreen $table_screen,
        ListScreenId $list_id,
        ?string $default = null
    ) {
        $this->formatters = $formatters;
        $this->context = $context;
        $this->default = $default;
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
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

        $filter = new ColumnValue(
            $this->context,
            $value->get_id(),
            $this->table_screen,
            $this->list_id
        );

        return $filter->apply_filter($value);
    }

    private function use_sanitize(Context $context, $id): bool
    {
        $filter = new ColumnValueSanitize(
            $context,
            $id,
            $this->table_screen,
            $this->list_id
        );

        return $filter->apply_filter();
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