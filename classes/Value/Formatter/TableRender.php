<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC;
use AC\Setting\Formatter;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;
use AC\Value\Formatter\Collection\Separator;

class TableRender implements Formatter
{

    private AC\Column $column;

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    public function __construct(AC\Column $column, TableScreen $table_screen, ListScreenId $list_id)
    {
        $this->column = $column;
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
    }

    public function format(Value $value): Value
    {
        $formatters = $this->column->get_formatters();

        // Nothing to format, return an empty string. Mostly original columns do not have any formatters.
        if ($formatters->count() === 0) {
            return $value->with_value('');
        }

        $formatters = $formatters->with_formatter(new Separator())
                                 ->with_formatter(
                                     new Column(
                                         $this->column->get_context(),
                                         $this->table_screen,
                                         $this->list_id
                                     )
                                 );

        $value = (new Aggregate($formatters))->format($value);

        return (new EmptyValue())->format($value);
    }

}