<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Column\Context;
use AC\Formatter;
use AC\Formatter\Collection\Separator;
use AC\FormatterCollection;
use AC\ListScreen;
use AC\TableScreen;
use AC\Type\Value;
use LogicException;

class TableRender implements Formatter
{

    private TableScreen $table_screen;

    private ListScreen $list_screen;

    private FormatterCollection $formatters;

    private Context $context;

    public function __construct(
        FormatterCollection $formatters,
        Context $context,
        TableScreen $table_screen,
        ListScreen $list_screen
    ) {
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
        $this->formatters = $formatters;
        $this->context = $context;
    }

    public function format(Value $value): Value
    {
        $aggregate = new Aggregate(
            $this->formatters->with_formatter(new Separator())
        );

        $value = $aggregate->format($value);

        if ( ! $value instanceof Value) {
            throw new LogicException('Invalid value.');
        }

        $formatter = new ColumnFilter(
            $this->context,
            $this->table_screen,
            $this->list_screen
        );

        $value = $formatter->format($value);

        return (new EmptyValue())->format($value);
    }

}