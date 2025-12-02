<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ListScreen;
use AC\Type\ColumnId;
use AC\Type\Value;
use AC\Value\Formatter\Aggregate;
use AC\Value\Formatter\ColumnFormatter;

class ListScreenValueFormatter implements ValueFormatter
{

    private ListScreen $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    public function format(ColumnId $id, Value $value): Value
    {
        $column = $this->list_screen->get_column($id);

        if ( ! $column) {
            return $value;
        }

        $value = (new Aggregate($column->get_formatters()))->format($value);

        $formatter = new ColumnFormatter(
            $column->get_context(),
            $this->list_screen->get_table_screen(),
            $this->list_screen->get_id()
        );

        return $formatter->format($value);
    }

}