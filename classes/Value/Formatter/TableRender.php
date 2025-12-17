<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Context;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;
use AC\Value\Formatter\Collection\Separator;

class TableRender implements Formatter
{

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    private FormatterCollection $formatters;

    private Context $context;

    public function __construct(
        FormatterCollection $formatters,
        Context $context,
        TableScreen $table_screen,
        ListScreenId $list_id
    ) {
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
        $this->formatters = $formatters;
        $this->context = $context;
    }

    public function format(Value $value): Value
    {
        $formatters = $this->formatters->with_formatter(new Separator())
                                       ->with_formatter(
                                           new ColumnFilter(
                                               $this->context,
                                               $this->table_screen,
                                               $this->list_id
                                           )
                                       );

        $value = (new Aggregate($formatters))->format($value);

        return (new EmptyValue())->format($value);
    }

}