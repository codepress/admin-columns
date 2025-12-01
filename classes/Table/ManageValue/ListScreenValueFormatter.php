<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

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

    private function get_formatter(ColumnId $id): ?ColumnFormatter
    {
        $column = $this->list_screen->get_column($id);

        if ( ! $column) {
            return null;
        }

        return new ColumnFormatter(
            $column->get_formatters(),
            $column->get_context(),
            $this->list_screen->get_table_screen(),
            $this->list_screen->get_id()
        );
    }

    private function get_cached_formatter(ColumnId $id): ?ColumnFormatter
    {
        static $formatters;

        if ( ! isset($formatters[(string)$id])) {
            $formatters[(string)$id] = $this->get_formatter($id);
        }

        return $formatters[(string)$id];
    }

    public function format(ColumnId $id, Value $value): Value
    {
        $formatter = $this->get_cached_formatter($id);

        if ( ! $formatter) {
            return $value;
        }

        return $formatter->format($value);
    }

}