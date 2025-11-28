<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\ListScreen;
use AC\Type\ColumnId;
use AC\Type\Value;

class ListScreenValueFormatter implements ValueFormatter
{

    private ListScreen $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    private function get_formatter(Column $column): ColumnFormatter
    {
        static $formatters = null;

        $id = (string)$column->get_id();

        if ( ! isset($formatters[$id])) {
            $formatters[$id] = new ColumnFormatter(
                $column->get_formatters(),
                $column->get_context(),
                $this->list_screen
            );
        }

        return $formatters[$id];
    }

    public function format(ColumnId $id, Value $value): Value
    {
        $column = $this->list_screen->get_column($id);

        if ( ! $column) {
            return $value;
        }

        return $this->get_formatter($column)
                    ->format($value);
    }

}