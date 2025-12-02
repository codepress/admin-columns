<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ListScreen;
use AC\Type\ColumnId;
use AC\Type\Value;
use AC\Value\Formatter\Aggregate;
use AC\Value\Formatter\Collection\Separator;
use AC\Value\Formatter\Column;
use AC\Value\Formatter\EmptyValue;

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
            return $value->with_value('');
        }

        $formatters = $column->get_formatters();

        if ($formatters->count() === 0) {
            return $value->with_value('');
        }

        $formatters = $formatters->with_formatter(new Separator())
                                 ->with_formatter(
                                     new Column(
                                         $column->get_context(),
                                         $this->list_screen->get_table_screen(),
                                         $this->list_screen->get_id()
                                     )
                                 );

        $value = (new Aggregate($formatters))->format($value);

        return (new EmptyValue())->format($value);
    }

}