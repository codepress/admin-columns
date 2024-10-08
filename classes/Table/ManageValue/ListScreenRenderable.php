<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\ListScreen;
use AC\TableScreen\ManageValue\GridRenderable;

class ListScreenRenderable implements GridRenderable
{

    private ListScreen $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    private function get_renderable(Column $column): ColumnRenderable
    {
        static $renderables = null;

        if ( ! isset($renderables[(string)$column->get_id()])) {
            $renderables[(string)$column->get_id()] = (new ColumnRenderable($column->get_formatters()));
        }

        return $renderables[(string)$column->get_id()];
    }

    public function render($column_id, $row_id): ?string
    {
        $column = $this->list_screen->get_column($column_id);
        
        if ( ! $column) {
            return null;
        }

        return $this->get_renderable($column)
                    ->render($row_id);
    }

}