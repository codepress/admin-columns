<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Formatter;
use AC\Formatter\TableRender;
use AC\ListScreen;
use AC\TableScreen;
use AC\Type\ColumnId;

class TableRenderFactory implements RenderFactory
{

    private ListScreen $list_screen;

    private TableScreen $table_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
        $this->table_screen = $list_screen->get_table_screen();
    }

    public function create(ColumnId $columnId): ?Formatter
    {
        $column = $this->list_screen->get_column($columnId);

        if ( ! $column) {
            return null;
        }

        $formatters = $column->get_formatters();

        if (0 === $formatters->count()) {
            return null;
        }

        return new TableRender(
            $formatters,
            $column->get_context(),
            $this->table_screen,
            $this->list_screen->get_id()
        );
    }

}