<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\CellRenderer;
use AC\Column;
use AC\ListScreen;
use AC\Setting\ContextFactory;
use AC\Type\ColumnId;

class ListScreenRenderable implements CellRenderer
{

    private ListScreen $list_screen;

    private ContextFactory $context_factory;

    public function __construct(ListScreen $list_screen, ContextFactory $context_factory)
    {
        $this->list_screen = $list_screen;
        $this->context_factory = $context_factory;
    }

    private function get_renderable(Column $column): ColumnRenderable
    {
        static $renderables = null;

        if ( ! isset($renderables[(string)$column->get_id()])) {
            $formatters = $column->get_formatters();

            $renderable = new ColumnRenderable(
                $formatters,
                $this->context_factory->create($column),
                $this->list_screen
            );

            $renderables[(string)$column->get_id()] = $renderable;
        }

        return $renderables[(string)$column->get_id()];
    }

    public function render_cell(string $column_id, $row_id): ?string
    {
        $column = $this->list_screen->get_column(new ColumnId($column_id));

        if ( ! $column) {
            return null;
        }

        return $this->get_renderable($column)
                    ->render($row_id);
    }

}